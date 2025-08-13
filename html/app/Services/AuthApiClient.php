<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthApiClient
{
  /**
   * @var PendingRequest
   */
  private PendingRequest $client;

  public function __construct()
  {
    $cfg = (array) config('services.backend', []);

    $client = Http::baseUrl($cfg['base_url'] ?? '')
      ->timeout((int) ($cfg['timeout'] ?? 8))
      ->connectTimeout((int) ($cfg['connect_timeout'] ?? 3))
      ->retry((int) ($cfg['retry'] ?? 1), (int) ($cfg['retry_delay_ms'] ?? 150))
      ->asJson();

    // Optional: allow self-signed certs in local dev if explicitly enabled
    if (app()->isLocal() && (bool) env('BACKEND_SKIP_TLS_VERIFY', false)) {
      $client = $client->withoutVerifying();
    }

    $this->client = $client;
  }

  /**
   * Perform login against the backend.
   *
   * @return array The JSON response (token, expires_in, user, roles, etc.)
   * @throws ConnectionException
   * @throws RequestException
   */
  public function login(string $email, string $password): array
  {
    $response = $this->client->post('/auth/login', [
      'email'    => $email,
      'password' => $password,
    ]);

    $this->throwIfError($response, 'Login failed');
    return (array) $response->json();
  }

  /**
   * Retrieve current user + roles.
   *
   * @return array The JSON response (user, roles)
   * @throws ConnectionException
   * @throws RequestException
   */
  public function me(string $token): array
  {
    $response = $this->client
      ->withToken($token)
      ->get('/auth/me');

    $this->throwIfError($response, 'Fetch current user failed');
    return (array) $response->json();
  }

  /**
   * Refresh the JWT.
   *
   * @return array The JSON response (token, expires_in)
   * @throws ConnectionException|RequestException
   */
  public function refresh(string $token): array
  {
    $response = $this->client
      ->withToken($token)
      ->post('/auth/refresh');

    $this->throwIfError($response, 'Token refresh failed');
    return (array) $response->json();
  }

  /**
   * Logout and invalidate the current token.
   * @throws RequestException
   * @throws ConnectionException
   */
  public function logout(string $token): void
  {
    $response = $this->client
      ->withToken($token)
      ->post('/auth/logout');

    // BE may return 204 No Content; treat any 2xx as success
    if ($response->failed()) {
      $this->throwIfError($response, 'Logout failed');
    }
  }

  /**
   * Throw if the backend response indicates an error.
   * @throws RequestException
   */
  private function throwIfError(Response $response, string $context): void
  {
    if ($response->successful()) {
      return;
    }

    // Optional lightweight logging (avoid sensitive data)
    try {
      Log::warning('Backend request failed', [
        'context' => $context,
        'status'  => $response->status(),
        'url'     => $response->effectiveUri() ? (string) $response->effectiveUri() : null,
      ]);
    } catch (Throwable $e) {
      // Log errors
      Log::error('Logging failed in ' . __METHOD__ . '() in ' . __FILE__ . ':' . __LINE__, [
        'exception' => $e->getMessage(),
      ]);
    }

    // Let Laravel wrap and throw a RequestException with useful detail
    $response->throw();
  }
}
