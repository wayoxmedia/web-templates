<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

readonly class AuthApiClient {

  public function __construct(private BackendApi $api) {}

  /**
   * Perform login against the backend.
   *
   * @return array The JSON response (token, expires_in, user, roles, etc.)
   * @throws RequestException|ConnectionException
   */
  public function login(string $email, string $password): array {
    $response = $this->api->post('/auth/login', [
      'email' => $email,
      'password' => $password,
    ]);

    return (array)$response->json();
  }

  /**
   * Retrieve current user + roles.
   *
   * @return array The JSON response (user, roles)
   * @throws ConnectionException|RequestException
   */
  public function me(string $token): array {
    $response = $this->api->get('/auth/me', [], true, $token);

    return (array)$response->json();
  }

  /**
   * Refresh the JWT.
   *
   * @return array The JSON response (token, expires_in)
   * @throws ConnectionException|RequestException
   */
  public function refresh(string $token): array {
    $response = $this->api->post('/auth/refresh', [], true, $token);

    return (array)$response->json();
  }

  /**
   * Logout and invalidate the current token.
   * @throws RequestException|ConnectionException
   */
  public function logout(string $token): void {
    $this->api->post('/auth/logout', [], true, $token);
  }
}
