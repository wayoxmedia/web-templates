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

class EnsureJwt
{
  // Session keys (keep in sync with FE AuthController)
  private const S_JWT        = 'auth.jwt';
  private const S_EXPIRES_AT = 'auth.expires_at';   // unix timestamp
  private const S_REFRESH_AT = 'auth.refresh_after';// unix timestamp

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
    $token     = (string) Session::get(self::S_JWT, '');
    $expiresAt = (int) Session::get(self::S_EXPIRES_AT, 0);

    if ($token === '' || $expiresAt <= 0) {
      return $this->toLogin($request);
    }

    // Small clock skew tolerance (5s)
    if ($now->timestamp >= ($expiresAt - 5)) {
      // Token expired → clear session and redirect to login
      $this->clearAuthSession();
      return $this->toLogin($request);
    }

    // Try refresh if it's time (~15 min) OR token is close to expiry (<=2 min)
    $refreshAfter = (int) Session::get(self::S_REFRESH_AT, 0);
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
      /** @var AuthApiClient $client */
      $client = app(AuthApiClient::class);

      $resp = $client->refresh($currentToken);

      $newToken  = $resp['token']      ?? $resp['access_token'] ?? null;
      $expiresIn = (int) ($resp['expires_in'] ?? 0);

      if (! is_string($newToken) || $newToken === '' || $expiresIn <= 0) {
        return false;
      }

      $now       = CarbonImmutable::now();
      $expiresAt = $now->addSeconds($expiresIn)->timestamp;
      $refreshAt = $now->addMinutes(15)->timestamp;

      Session::put(self::S_JWT,        $newToken);
      Session::put(self::S_EXPIRES_AT, $expiresAt);
      Session::put(self::S_REFRESH_AT, $refreshAt);
      Session::save();

      return true;
    } catch (\Throwable $e) {
      try {
        Log::warning('JWT auto-refresh failed', [
          'error' => $e->getMessage(),
          'class' => get_class($e),
        ]);
      } catch (\Throwable $ignored) {
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
    Session::forget([
      self::S_JWT,
      self::S_EXPIRES_AT,
      self::S_REFRESH_AT,
      'auth.user', 'auth.roles']
    );
    Session::save();
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
