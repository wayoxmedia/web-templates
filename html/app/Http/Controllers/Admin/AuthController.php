<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\BackendServiceException;
use App\Http\Controllers\Controller;
use App\Services\AuthApiClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Client\RequestException;
use RuntimeException;
use Throwable;

class AuthController extends Controller
{
  private const SESSION_BACKEND_JWT_TOKEN  = 'backend.jwt';
  private const SESSION_BACKEND_EXPIRES_AT = 'backend.expires_at';
  private const SESSION_BACKEND_USER       = 'backend.user';
  private const SESSION_BACKEND_ROLES      = 'backend.roles';
  private const SESSION_BACKEND_REFRESH_AT = 'backend.refresh_after';

  public function __construct(private readonly AuthApiClient $client) {}

  /**
   * Show the login form.
   *
   * @return View|RedirectResponse
   */
  public function showLogin(): View|RedirectResponse
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
  public function showForgot(): View
  {
    return view('auth.pages.forgot');
  }

  /**
   * Handle login submission and persist auth data in session.
   *
   * @param Request $request
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function submitLogin(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'email'    => ['required', 'string', 'email', 'min:6', 'max:100'],
      'password' => ['required', 'string', 'min:8'],
    ]);

    try {
      $resp = $this->client->login($validated['email'], $validated['password']);
    } catch (RequestException $e) {
      // Backend returned non-2xx or is unreachable
      // Process error and show generic message to avoid leaking information
      $status = $e->response?->status();
      logger()->info(
        'Backend Login Failed',
        [
          'email'  => $validated['email'],
          'code'   => $e->getCode(),
          'status' => $status,
          'message' => $e->getMessage() ?? 'No message provided',
          'uri'     => method_exists($e->response, 'effectiveUri')
            ? (string) $e->response->effectiveUri()
            : 'n/a',
          //'trace'    => $e->getTraceAsString(),
          //'response' => $e->response?->body(),
        ]
      );

      $this->beautifyException($e, $status);
    } catch (Throwable $e) {
      // Catch-all for unexpected errors

      logger()->error('Unexpected Login error', [
        'email'  => $validated['email'],
        'code'   => $e->getCode(),
        'message' => $e->getMessage() ?? 'No message provided',
        //'trace'    => $e->getTraceAsString(),
      ]);

      return redirect()
        ->back()
        ->withInput($request->only('email'))
        ->withErrors([
          'credentials' => __('An unexpected error occurred. Please try again later.'),
        ]);
    }

    $this->storeAuthInSession($resp);
    $request->session()->regenerate(); // anti-fixation

    // Redirect to intended admin area
    return redirect()->intended(route('admin.dashboard'));
  }

  /**
   * Logout: invalidate token on backend and clear local session.
   *
   * @param Request $request
   * @return RedirectResponse
   */
  public function logout(Request $request): RedirectResponse
  {
    $token = Session::get(self::SESSION_BACKEND_JWT_TOKEN);

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
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login')->with('status', __('You have been logged out.'));
  }

  /**
   * Persist token, user and roles in PHP session.
   *
   * @param array $payload The response payload from the backend.
   * @throws RuntimeException on invalid payload.
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

    Session::put(self::SESSION_BACKEND_JWT_TOKEN, $token);
    Session::put(self::SESSION_BACKEND_USER, $user);
    Session::put(self::SESSION_BACKEND_ROLES, array_values((array) $roles));
    Session::put(self::SESSION_BACKEND_REFRESH_AT, $refreshAfter);
    Session::put(self::SESSION_BACKEND_EXPIRES_AT, $expiresAt);
    Session::save();
  }

  /**
   * Clear auth-related keys from session.
   */
  private function clearAuthSession(): void
  {
    /** @var Store $store */
    $store = app('session');
    $store->forget([
      self::SESSION_BACKEND_JWT_TOKEN,
      self::SESSION_BACKEND_USER,
      self::SESSION_BACKEND_ROLES,
      self::SESSION_BACKEND_REFRESH_AT,
    ]);
    Session::save();
  }

  /**
   * Check if we have a non-expired token in session.
   */
  private function isAuthenticated(): bool
  {
    $token     = Session::get(self::SESSION_BACKEND_JWT_TOKEN);
    $expiresAt = (int) Session::get(self::SESSION_BACKEND_EXPIRES_AT, 0);

    if (!is_string($token) || $token === '' || $expiresAt <= 0) {
      return false;
    }

    // Small clock skew tolerance: 5 seconds
    return Carbon::now()->timestamp < ($expiresAt - 5);
  }

  /**
   * @throws ValidationException
   */
  private function beautifyException($e, $status) {
    // Prefer a friendly message; fallback if backend didn’t send 'error'.
    if (isset($e->response) && method_exists($e->response, 'json')) {
      // If the response is JSON, we can extract the error message.
      // This is common in APIs that return structured error responses.
      $backendMsg = data_get($e->response?->json(), 'error');
    } else {
      // If the response is not JSON or doesn't have a body, we use the exception message.
      $backendMsg = $e->getMessage();
    }

    // Map common statuses to nicer messages.
    $friendly = match ($status) {
      401 => __('Authentication failed. Please check your credentials.'),
      429 => __('Too many attempts. Please try again in a moment.'),
      503, 502, 504 => __('Service unavailable. Please try again shortly.'),
      default => $backendMsg ?? __('Unexpected error. Please try again.'),
    };

    throw ValidationException::withMessages([
      // "credentials" is a pseudo-field we will render in #login-error
      'credentials' => $friendly,
    ])->redirectTo(url()->previous()); // keeps standard back() behavior
  }
}
