<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthApiClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Client\RequestException;
use RuntimeException;
use Throwable;

class AuthController extends Controller
{
  private const SESSION_JWT        = 'auth.jwt';
  private const SESSION_EXPIRES_AT = 'auth.expires_at'; // unix timestamp
  private const SESSION_USER       = 'auth.user';
  private const SESSION_ROLES      = 'auth.roles';
  private const SESSION_REFRESH_AT = 'auth.refresh_after'; // unix timestamp (for step 7: middleware refresh)

  public function __construct(private readonly AuthApiClient $client)
  {
  }

  /**
   * Show the login form.
   *
   * @param Request $request
   * @return View|RedirectResponse
   */
  public function showLogin(Request $request): View|RedirectResponse
  {
    if ($this->isAuthenticated()) {
      return redirect()->route('admin.dashboard');
    }

    return view('auth.pages.login');
  }

  /**
   * Show the "forgot password" form.
   *
   * @return View
   */
  public function showForgot(): View {
    return view('auth.pages.forgot');
  }

  /**
   * Handle login submission and persist auth data in session.
   *
   * @param Request $request
   * @return RedirectResponse
   * @throws ValidationException|ConnectionException
   */
  public function submitLogin(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'email'    => ['required', 'string', 'email', 'min:6'],
      'password' => ['required', 'string', 'min:8'],
    ]);

    try {
      $resp = $this->client->login($validated['email'], $validated['password']);
    } catch (RequestException $e) {
      // Backend returned non-2xx or is unreachable
      // Process error and show generic message to avoid leaking information
      $status = $e->response?->status();
      logger()->warning(
        'Login failed',
        [
          'email'  => $validated['email'],
          'code'   => $e->getCode(),
          'status' => $status,
          //'trace'    => $e->getTraceAsString(),
          //'response' => $e->response?->body(),
        ]
      );

      // Prefer a friendly message; fallback if backend didn’t send 'error'.
      $backendMsg = data_get($e->response?->json(), 'error');

      // Map common statuses to nicer messages.
      $friendly = match ($status) {
        401 => __('Authentication failed. Please check your credentials.'),
        429 => __('Too many attempts. Please try again in a moment.'),
        503, 502, 504 => __('Service unavailable. Please try again shortly.'),
        default => __('Unexpected error. Please try again.'),
      };

      throw ValidationException::withMessages([
        // "credentials" is a pseudo-field we will render in #login-error
        'credentials' => $backendMsg ?: $friendly,
      ])->redirectTo(url()->previous()); // keeps standard back() behavior
    }

    $this->storeAuthInSession($resp);

    // Redirect to intended admin area
    return redirect()->intended(route('admin.dashboard'));
  }

  /**
   * Logout: invalidate token on backend and clear local session.
   */
  public function logout(Request $request): RedirectResponse
  {
    $token = Session::get(self::SESSION_JWT);

    if (is_string($token) && $token !== '') {
      try {
        $this->client->logout($token);
      } catch (Throwable $e) {
        // Keep logout idempotent, ignore backend errors
        logger()->error('Logout failed', [
          'message' => $e->getMessage(),
          'token'   => $token,
        ]);
        // Optionally, you could flash an error message to the session
        // return redirect()->route('admin.dashboard')->withErrors(__('Logout failed, please try again.'));
      }
    }

    $this->clearAuthSession();

    return redirect()->route('admin.login')->with('status', __('You have been logged out.'));
  }

  /**
   * Persist token, user and roles in PHP session.
   */
  private function storeAuthInSession(array $payload): void
  {
    $token      = $payload['token'] ?? $payload['access_token'] ?? null;
    $expiresIn  = (int) ($payload['expires_in'] ?? 0);
    $user       = $payload['user']  ?? [];
    $roles      = $payload['roles'] ?? [];

    if (!is_string($token) || $token === '' || $expiresIn <= 0) {
      throw new RuntimeException('Invalid auth payload from backend.');
    }

    $now        = Carbon::now();
    $expiresAt  = $now->copy()->addSeconds($expiresIn)->timestamp;

    // For the refresh middleware (step 7), we set a refresh-after mark at ~15 minutes
    $refreshAfter = $now->copy()->addMinutes(15)->timestamp;

    Session::put(self::SESSION_JWT, $token);
    Session::put(self::SESSION_EXPIRES_AT, $expiresAt);
    Session::put(self::SESSION_USER, $user);
    Session::put(self::SESSION_ROLES, array_values((array) $roles));
    Session::put(self::SESSION_REFRESH_AT, $refreshAfter);

    // Regenerate session ID to mitigate fixation
    Session::migrate(true);
  }

  /**
   * Clear auth-related keys from session.
   */
  private function clearAuthSession(): void
  {
    Session::forget([
      self::SESSION_JWT,
      self::SESSION_EXPIRES_AT,
      self::SESSION_USER,
      self::SESSION_ROLES,
      self::SESSION_REFRESH_AT,
    ]);
    Session::save();
  }

  /**
   * Check if we have a non-expired token in session.
   */
  private function isAuthenticated(): bool
  {
    $token     = Session::get(self::SESSION_JWT);
    $expiresAt = (int) Session::get(self::SESSION_EXPIRES_AT, 0);

    if (!is_string($token) || $token === '' || $expiresAt <= 0) {
      return false;
    }

    // Small clock skew tolerance: 5 seconds
    return Carbon::now()->timestamp < ($expiresAt - 5);
  }
}
