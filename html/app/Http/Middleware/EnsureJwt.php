<?php

namespace App\Http\Middleware;

use App\Services\AuthApiClient;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

readonly class EnsureJwt
{
  // Session keys (keep in sync with FE AuthController)
  private const SESSION_BACKEND_JWT_TOKEN  = 'backend.jwt';
  private const SESSION_BACKEND_EXPIRES_AT = 'backend.expires_at';
  private const SESSION_BACKEND_USER       = 'backend.user';
  private const SESSION_BACKEND_ROLES      = 'backend.roles';
  private const SESSION_BACKEND_REFRESH_AT = 'backend.refresh_after';

  public function __construct(private AuthApiClient $auth) {}

  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response|RedirectResponse
  {
    // Skip for login routes to avoid loops
    if ($request->routeIs(['admin.login', 'admin.login.submit'])) {
      return $next($request);
    }

    $now       = CarbonImmutable::now();
    $token     = (string) Session::get(self::SESSION_BACKEND_JWT_TOKEN, '');
    $expiresAt = (int) Session::get(self::SESSION_BACKEND_EXPIRES_AT, 0);

    if ($token === '' || $expiresAt <= 0) {
      return $this->toLogin($request);
    }

    // Small clock skew tolerance (5s)
    if ($now->timestamp >= ($expiresAt - 5)) {
      // Token expired → clear session and redirect to login page
      $this->clearAuthSession();
      return $this->toLogin($request);
    }

    // Try refresh if it's time (~15 min) OR token is close to expiry (<=2 min)
    $refreshAfter = (int) Session::get(self::SESSION_BACKEND_REFRESH_AT, 0);
    $closeToExpiry = $now->addMinutes(2)->timestamp >= $expiresAt;

    if (($refreshAfter > 0 && $now->timestamp >= $refreshAfter) || $closeToExpiry) {
      if (! $this->attemptRefresh($token)) {
        $this->clearAuthSession();
        return $this->toLogin($request);
      }
    }

    return $next($request);
  }

  /**
   * Attempt to refresh token; update session on success.
   */
  private function attemptRefresh(string $currentToken): bool
  {
    try {

      $resp = $this->auth->refresh($currentToken);

      $newToken  = $resp['token'] ?? $resp['access_token'] ?? null;
      $expiresIn = (int) ($resp['expires_in'] ?? 0);

      if (! is_string($newToken) || $newToken === '' || $expiresIn <= 0) {
        return false;
      }

      $now       = CarbonImmutable::now();
      $expiresAt = $now->addSeconds($expiresIn)->timestamp;
      $refreshAt = $now->addMinutes(15)->timestamp;

      Session::put(self::SESSION_BACKEND_JWT_TOKEN,  $newToken);
      Session::put(self::SESSION_BACKEND_EXPIRES_AT, $expiresAt);
      Session::put(self::SESSION_BACKEND_REFRESH_AT, $refreshAt);
      Session::save();

      return true;
    } catch (Throwable $e) {
      try {
        Log::warning('JWT auto-refresh failed', [
          'error' => $e->getMessage(),
          'class' => get_class($e),
        ]);
      } catch (Throwable $ignored) {
        // ignore logging failures
      }
      return false;
    }
  }

  /**
   * Clear auth data from session.
   */
  private function clearAuthSession(): void
  {
    $store = session();
    $store->forget([
      self::SESSION_BACKEND_JWT_TOKEN,
      self::SESSION_BACKEND_EXPIRES_AT,
      self::SESSION_BACKEND_REFRESH_AT,
      self::SESSION_BACKEND_USER,
      self::SESSION_BACKEND_ROLES,
    ]);
    $store->save();
  }

  /**
   * Redirect to log in with intended URL.
   */
  private function toLogin(Request $request): RedirectResponse
  {
    // Store intended URL in session so redirect()->intended() works after login
    session()->put('url.intended', $request->fullUrl());

    return redirect()
      ->route('admin.login')
      ->with('status', __('Please sign in to continue.'));
  }
}
