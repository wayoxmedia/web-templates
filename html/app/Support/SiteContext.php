<?php

namespace App\Support;

use App\Services\BackendApi;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class SiteContext
{
  private const KEY_CTX         = 'site.ctx';           // payload completo (array)
  private const KEY_DOMAIN      = 'site.domain';        // dominio al que pertenece
  private const KEY_TEMPLATE    = 'site.template.slug'; // acceso rápido
  private const KEY_CACHE_UNTIL = 'site.cache_until';   // timestamp (int)

  public static function templateSlug(): ?string
  {
    $slug = Session::get(self::KEY_TEMPLATE);
    return is_string($slug) && $slug !== '' ? $slug : null;
  }

  /**
   * Returns the current site context from session.
   */
  public static function fromSession(string $domain): ?array
  {
    $savedDomain = (string) Session::get(self::KEY_DOMAIN, '');
    $until       = (int) Session::get(self::KEY_CACHE_UNTIL, 0);

    if ($savedDomain !== '' && 0 !== strcasecmp($savedDomain, $domain)) {
      return null; // contexto es de otro dominio
    }
    if ($until > 0 && time() > $until) {
      return null; // vencido
    }
    $ctx = Session::get(self::KEY_CTX);
    return is_array($ctx) ? $ctx : null;
  }

  /**
   * Ensure a site context is available for the given domain.
   * If it exists in session, returns it. If not, calls the backend resolver,
   * saves the result in session, and returns it.
   */
  public static function remember(BackendApi $api, string $domain, int $sessionTtl = 600): array
  {
    $ctx = self::fromSession($domain);
    if ($ctx && is_array($ctx)) {
      return $ctx;
    }

    // Llama al backend; tu BackendApi ya cachea a nivel de app (Cache::remember)
    $resolved = $api->resolveSiteByDomain($domain);

    self::saveFromResolve($resolved, $domain, $sessionTtl);

    return $resolved;
  }

  /**
   * For convenience, we will store the site context in session.
   * This allows us to access the current site and tenant easily.
   */
  public static function saveFromResolve(array $payload, string $domain, int $ttlSeconds = 600): void
  {
    $slug = Arr::get($payload, 'template.slug');

    Session::put(self::KEY_CTX, $payload);
    Session::put(self::KEY_DOMAIN, $domain);
    if (is_string($slug) && $slug !== '') {
      Session::put(self::KEY_TEMPLATE, $slug);
    }
    Session::put(self::KEY_CACHE_UNTIL, time() + max(1, $ttlSeconds));
    Session::save();
  }

  /**
   * Cleans the context from session.
   * This is useful when switching sites or domains, or when the context is no longer valid
   */
  public static function clear(): void
  {
    $store = session();
    $store->forget([
      self::KEY_CTX,
      self::KEY_DOMAIN,
      self::KEY_TEMPLATE,
      self::KEY_CACHE_UNTIL,
    ]);
    $store->save();
  }
}
