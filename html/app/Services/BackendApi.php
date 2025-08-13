<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class BackendApi
{
  protected string $baseUrl;
  protected int $timeout;
  protected ?string $token;
  protected int $cacheTtl;
  protected array $defaultHeaders;
  protected string $base;
  protected string $resolverUrl;

  public function __construct()
  {
    $cfg = config('api');
    $this->baseUrl        = rtrim($cfg['base_url'], '/');
    $this->timeout        = $cfg['timeout'];
    $this->token          = $cfg['token'];
    $this->cacheTtl       = $cfg['cache_ttl'];
    $this->defaultHeaders = $cfg['default_headers'] ?? [];

    $this->base = rtrim(env('BACKEND_API_BASE_URL', 'http://mystorepanel'), '/');
    $this->resolverUrl = rtrim(env('SITE_RESOLVER_URL', $this->base.'/api/sites/resolve'), '/');
  }

  protected function client()
  {
    $client = Http::baseUrl($this->baseUrl)
      ->withHeaders($this->defaultHeaders)
      ->timeout($this->timeout);

    // Attach Bearer token if present
    if ($this->token) {
      $client = $client->withToken($this->token);
    }

    return $client;
  }

  /**
   * Resolve a Site from a domain via API.
   * Expected response shape:
   * {
   *   "site": {"id": 1, "domain": "example.com"},
   *   "tenant": {"id": 10, "name": "Acme", "slug": "acme"},
   *   "template": {"id": 2, "slug": "modern", "name": "Modern"},
   *   "settings": {"branding": {...}, "header": {...}}
   * }
   */
  public function resolveSiteByDomain(string $domain): array
  {
    $key = "api:resolve-site:{$domain}";

    return Cache::remember($key, $this->cacheTtl, function () use ($domain) {
      try {
        $res = $this->client()->get('sites/resolve', ['domain' => $domain]);
        /*
        logger()->debug('API call', [
          'status' => $res->status(),
          'url'    => $res->handlerStats()['url'] ?? null, // URL final que se llamó
          'uri'    => method_exists($res, 'effectiveUri') ? (string) $res->effectiveUri() : 'n/a',
          'body'   => $res->body(),
        ]);
        */
        $res->throw();
        return $res->json();
      } catch (ConnectionException|RequestException $e) {
        // You may log the exception here
        logger()->error(
          'API call failed at ' . __METHOD__ . '() in ' . __FILE__ . ':' . __LINE__,
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
   *     "id": 123, "slug": "/", "title": "Home",
   *     "content": { "hero": {...}, "features": [...] },
   *     "meta_title": "Home | Acme",
   *     "meta_description": "..."
   *   }
   * }
   */
  public function getPageBySlug(int $tenantId, string $slug): array
  {
    // $norm = $slug === '' ? '/' : $slug;
    $norm = trim($slug);
    if ($norm === '' || $norm === '/') {
      $norm = '/';
    } else {
      $norm = preg_replace('#/+#', '/', $norm);
      if (! str_starts_with($norm, '/')) {
        $norm = '/'.$norm;
      }
      $norm = rtrim($norm, '/') ?: '/';
    }
    //$slug = ltrim($slug, '/'); // important to avoid "%2Fhome"
    //$url  = "{$this->base}/api/tenants/{$tenantId}/pages";

    $key  = "api:page:tenant:{$tenantId}:slug:{$norm}";

    return Cache::remember($key, $this->cacheTtl, function () use ($tenantId, $norm) {
      try {
        $res = $this->client()->get("tenants/{$tenantId}/pages", ['slug' => $norm]);
        /**/
        logger()->debug('API call', [
          'status' => $res->status(),
          'url'    => $res->handlerStats()['url'] ?? null, // URL final que se llamó
          'uri'    => method_exists($res, 'effectiveUri') ? (string) $res->effectiveUri() : 'n/a',
          'body'   => $res->body(),
        ]);
        /**/
        $res->throw();
        return $res->json();
      } catch (ConnectionException|RequestException $e) {
        logger()->error(
          'API call failed at ' . __METHOD__ . '() in ' . __FILE__ . ':' . __LINE__,
          ['exception' => $e->getMessage()]
        );
        throw $e;
      }
    });
  }

  /**
   * Optionally fetch theme settings separately (if not included in resolve).
   * Expected response: {"settings": {...}}
   */
  public function getThemeSettings(int $tenantId, int $templateId): array
  {
    $key = "api:theme-settings:tenant:{$tenantId}:template:{$templateId}";

    return Cache::remember($key, $this->cacheTtl, function () use ($tenantId, $templateId) {
      try {
        $res = $this->client()->get("tenants/{$tenantId}/templates/{$templateId}/settings");
        $res->throw();
        return $res->json();
      } catch (ConnectionException|RequestException $e) {
        throw $e;
      }
    });
  }
}
