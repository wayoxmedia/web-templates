<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use RuntimeException;

class BackendApi {
  protected string $root;
  protected int $timeout;
  protected int $connectTimeout;
  protected int $retry;
  protected int $retryDelayMs;
  protected ?string $token;
  protected int $cacheTtl;
  protected array $defaultHeaders;

  /**
   * BackendApi constructor.
   *
   * Initializes the API client with configuration from services.backend.
   * Sets up base URL, headers, timeouts, and other settings.
   */
  public function __construct() {
    $cfg = (array)config('services.backend', []);

    $base = rtrim($cfg['base_url'] ?? 'http://mystorepanel.test', '/');
    $prefix = '/' . ltrim($cfg['api_prefix'] ?? '/api', '/');
    $this->root = $base . $prefix; // ej: http://mystorepanel.test/api

    $this->timeout = $cfg['timeout'] ?? 10;
    $this->connectTimeout = (int)($cfg['connect_timeout'] ?? 5);
    $this->retry = (int)($cfg['retry'] ?? 1);
    $this->retryDelayMs = (int)($cfg['retry_delay_ms'] ?? 150);
    $this->cacheTtl = $cfg['cache_ttl'] ?? 600; // seconds

    // ALWAYS send Service Token
    $serviceToken = (string)($cfg['service_token'] ?? 'MISSING TOKEN');
    $this->defaultHeaders = array_merge(
      (array)($cfg['default_headers'] ?? []),
      [
        'Accept' => 'application/json',
        'X-Service-Token' => $serviceToken,
      ]
    );
  }

  /**
   * GET /api/{path}
   * This method allows you to retrieve data from the specified API path.
   * @param string $path The API path to get data from.
   * @param array $query Optional query parameters to include in the request.
   * @param bool $withAuth Whether to include authentication token.
   *                       If true, it will use the token from the session or the provided one.
   * @param string|null $token Optional token to use instead of the session token.
   * @return PromiseInterface|Response The response from the API.
   *                                   It can be a PromiseInterface for async handling or a Response object.
   * @throws RequestException|ConnectionException
   */
  public function get(
    string  $path,
    array   $query = [],
    bool    $withAuth = false,
    ?string $token = null
  ): PromiseInterface|Response {
    $res = $this->http($withAuth, $token)->get($this->u($path), $query);
    $res->throw();
    return $res;
  }

  /**
   * POST JSON /api/{path}
   * This method allows you to create a new resource at the specified path.
   * @param string $path The API path to post to.
   * @param array $json The JSON data to send in the request body.
   * @param bool $withAuth Whether to include authentication token.
   *                       If true, it will use the token from the session or the provided one.
   * @param string|null $token Optional token to use instead of the session token.
   * @return PromiseInterface|Response The response from the API.
   *                                   It can be a PromiseInterface for async handling or a Response object.
   * @throws RequestException|ConnectionException
   */
  public function post(
    string  $path,
    array   $json = [],
    bool    $withAuth = false,
    ?string $token = null): PromiseInterface|Response {
    $res = $this->http($withAuth, $token)->asJson()->post($this->u($path), $json);
    $res->throw();
    return $res;
  }

  /**
   * PUT JSON /api/{path}
   * This method allows you to update a resource at the specified path.
   *
   * @param string $path The API path to update.
   * @param array $json The JSON data to send in the request body.
   * @param bool $withAuth Whether to include authentication token.
   *                       If true, it will use the token from the session or the provided one.
   * @param string|null $token Optional token to use instead of the session token.
   * @return PromiseInterface|Response The response from the API.
   *                                   It can be a PromiseInterface for async handling or a Response object.
   * @throws ConnectionException|RequestException
   */
  public function put(
    string  $path,
    array   $json = [],
    bool    $withAuth = false,
    ?string $token = null
  ): PromiseInterface|Response {
    $res = $this->http($withAuth, $token)->asJson()
      ->put($this->u($path), $json);
    $res->throw();
    return $res;
  }

  /**
   * DELETE /api/{path}
   * This method allows you to delete a resource at the specified path.
   *
   * @param string $path The API path to delete.
   * @param array $query Optional query parameters to include in the request.
   * @param bool $withAuth Whether to include authentication token.
   *                       If true, it will use the token from the session or the provided one.
   * @param string|null $token Optional token to use instead of the session token.
   * @return PromiseInterface|Response The response from the API.
   *                                   It can be a PromiseInterface for async handling or a Response object.
   * @throws RequestException|ConnectionException
   */
  public function delete(
    string  $path,
    array   $query = [],
    bool    $withAuth = false,
    ?string $token = null
  ): PromiseInterface|Response {
    $res = $this->http($withAuth, $token)->delete($this->u($path), $query);
    $res->throw();
    return $res;
  }

  /**
   * Create a new HTTP request with common settings.
   * This is used internally by all methods to avoid code duplication.
   * It sets the base URL, headers, timeout, and optionally adds a Bearer token.
   * @param bool $withAuth Whether to include authentication token.
   *                       If true, it will use the token from the session or the provided one.
   * @param string|null $token
   * @return PendingRequest
   */
  protected function http(bool $withAuth = false, ?string $token = null): PendingRequest {
    $req = Http::baseUrl($this->root)
      ->acceptJson()
      ->withHeaders($this->defaultHeaders)
      ->timeout($this->timeout)
      ->connectTimeout($this->connectTimeout)
      ->retry($this->retry, $this->retryDelayMs);

    if ($withAuth) {
      $token = $token ?? session('backend.token');
      if (!is_string($token) || $token === '') {
        throw new RuntimeException('Missing user JWT in session (backend.token).');
      }
      $req = $req->withToken($token);
    }

    return $req;
  }

  /**
   * Normalice path
   */
  protected function u(string $path): string {
    $path = '/' . ltrim($path, '/');
    return preg_replace('#//+#', '/', $path) ?: '/';
  }

  /**
   * Resolve a Site from a domain via API.
   *
   * Expected response shape:
   * {
   *   "site": {"id": 1, "domain": "example.com"},
   *   "tenant": {"id": 10, "name": "Acme", "slug": "acme"},
   *   "template": {"id": 2, "slug": "modern", "name": "Modern"},
   *   "settings": {"branding": {...}, "header": {...}}
   * }
   * @param string $domain The domain to resolve (e.g. "example.com").
   * @return array The resolved site data.
   */
  public function resolveSiteByDomain(string $domain): array {
    $key = "api:resolve-site:{$domain}";

    return Cache::remember($key, $this->cacheTtl, function () use ($domain) {
      try {
        $res = $this->get('sites/resolve', ['domain' => $domain]);
        logger()->debug(
          'API call (resolveSiteByDomain)',
          [
            'status' => $res->status(),
            'domain' => $domain,
            'url' => $res->handlerStats()['url'] ?? null, // Final URL called.
            'uri' => method_exists($res, 'effectiveUri') ? (string)$res->effectiveUri() : 'n/a',
            'body' => $res->body(),
          ]
        );
        return (array)$res->json();
      } catch (Exception $e) {
        logger()->error(
          'API call (resolveSiteByDomain) failed for domain: ' . $domain,
          ['exception' => $e->getMessage()]
        );

        throw $e;
      }
    });
  }

  /**
   * Fetch a page by tenant + slug.
   * Expected response shape:
   * {
   *   "page": {
   *     "id": 123,
   *     "slug": "/",
   *     "title": "Home",
   *     "content": { "hero": {...}, "features": [...] },
   *     "meta_title": "Home | Acme",
   *     "meta_description": "..."
   *   }
   * }
   */
  public function getPageBySlug(int $tenantId, string $slug): array {
    $norm = trim($slug);
    if ($norm === '' || $norm === '/') {
      $norm = '/';
    } else {
      $norm = preg_replace('#/+#', '/', $norm);
      if (!str_starts_with($norm, '/')) {
        $norm = '/' . $norm;
      }
      $norm = rtrim($norm, '/') ?: '/';
    }

    $key = "api:page:tenant:{$tenantId}:slug:{$norm}";

    return Cache::remember($key, $this->cacheTtl, function () use ($tenantId, $norm) {
      try {
        $res = $this->get("tenants/{$tenantId}/pages", ['slug' => $norm]);
        logger()->debug('API call (getPageBySlug)', [
          'status' => $res->status(),
          'url' => $res->handlerStats()['url'] ?? null, // URL final que se llamó
          'uri' => method_exists($res, 'effectiveUri') ? (string)$res->effectiveUri() : 'n/a',
          'body' => $res->body(),
        ]);
        return $res->json();
      } catch (Exception $e) {
        logger()->error(
          'API call failed  (getPageBySlug)',
          [
            'tenantId' => $tenantId,
            'slug' => $norm,
            'exception' => $e->getMessage()
          ]
        );
        throw $e;
      }
    });
  }

  /**
   * Optionally fetch theme settings separately (if not included in resolve).
   *
   * Expected response: {"settings": {...}}
   *
   * @param int $tenantId The tenant ID.
   * @param int $templateId The template ID.
   * @return array The theme settings.
   */
  public function getThemeSettings(int $tenantId, int $templateId): array {
    $key = "api:theme-settings:tenant:{$tenantId}:template:{$templateId}";

    return Cache::remember($key, $this->cacheTtl, function () use ($tenantId, $templateId) {
      try {
        $res = $this->get("tenants/{$tenantId}/templates/{$templateId}/settings");
        return $res->json();
      } catch (Exception $e) {
        logger()->error(
          'API call failed (getThemeSettings)',
          [
            'tenantId' => $tenantId,
            'templateId' => $templateId,
            'exception' => $e->getMessage(),
          ]);
        throw $e;
      }
    });
  }
}
