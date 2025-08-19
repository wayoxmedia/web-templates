<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ViewAlias;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\View;
use App\Services\BackendApi;

class PageController extends Controller
{
  public function __construct(private readonly BackendApi $api) {}

  /**
   * Resolve page by slug via Backend API and render using the active theme.
   */
  public function show(?string $slug = null): ViewAlias|Application|Factory {
    // Retrieve tenant and template from middleware-injected instances
    $tenant   = app('tenant');         // stdClass with ->id, ->slug, etc.
    $template = app('template');       // stdClass with ->slug
    $themeSettings = app('theme_settings'); // array

    // Determine what "home" means (fallback to "home")
    $homeFallback  = is_array($themeSettings) && isset($themeSettings['home_slug'])
      ? (string) $themeSettings['home_slug']
      : 'home';

    // Normalize slug: null / "" / "/" -> $homeFallback; trim leading/trailing slashes
    $slug = $this->normalizeSlug($slug, $homeFallback);

    logger()->debug(
      'Normalized page slug',
      ['slug' => $slug, 'tenant_id' => $tenant->id ?? null]
    );

    // Fetch page from Backend API
    $response = $this->api->getPageBySlug($tenant->id, $slug);

    if (!is_array($response) || empty($response['ok'])) {
      abort(404, 'Page not found');
    }

    $page = $response['data'] ?? null;
    if (! $page) {
      abort(404, 'Page not found.');
    }

    // Common data for the view
    $data = [
      'page'          => $page,
      'content'       => $page['content'] ?? [],
      'settings'      => $themeSettings,
      'tenant'        => $tenant,
      'template'      => $template,
      'slug'          => $slug,
      'homeFallback'  => $homeFallback,
    ];

    // Candidate view names inside the theme
    $candidates = $this->buildCandidates($slug, $homeFallback);

    // Try "theme::" first, then "themeBase::"
    foreach ($candidates as $candidate) {
      if (View::exists("theme::{$candidate}")) {
        return view("theme::{$candidate}", $data);
      }
    }

    // Then try base theme fallbacks
    foreach ($candidates as $candidate) {
      if (View::exists("themeBase::{$candidate}")) {
        return view("themeBase::{$candidate}", $data);
      }
    }

    // Last resort: ensure a generic base view exists and use it
    return view('themeBase::pages.generic', $data);
  }

  /**
   * Build candidate view list based on the slug.
   * "/" => ["pages.home", "pages.index", "pages.generic"]
   * "about" => ["pages.about", "pages.generic"]
   * "products/sauce-xyz" => ["pages.products.sauce-xyz", "pages.products.index", "pages.generic"]
   */
  protected function buildCandidates(string $slug, string $homeFallback): array
  {
    // Normalized earlier, pero igual aseguramos:
    $slug = trim($slug, "/ \t\n\r\0\x0B");
    $isHome = ($slug === $homeFallback);

    // Para nombres de vistas Blade usamos puntos, no slashes
    $dotSlug = $slug === '' ? $homeFallback : str_replace('/', '.', $slug);

    // Primer prefijo (para fallback pages.<prefix>.index)
    $segments = explode('.', $dotSlug);
    $prefix   = 'pages.' . ($segments[0] ?? 'generic');

    // Candidatos ordenados (theme:: primero, luego themeBase:: en el controller)
    $candidates = [
      // Home dedicados
      $isHome ? 'pages.home' : null,
      $isHome ? 'home'       : null,

      // Vista específica exacta: pages.products.sauce-xyz
      "pages.{$dotSlug}",

      // Fallback “sección”: pages.products.index
      "{$prefix}.index",

      // Vistas genéricas del theme
      $isHome ? 'home' : 'page',

      // Último recurso (debe existir en templates/default)
      'pages.generic',
    ];

    // Limpia nulls y duplicados
    return array_values(array_unique(array_filter($candidates)));
  }

  /**
   * @param string|null $slug
   * @param string $homeFallback
   * @return string
   */
  private function normalizeSlug(?string $slug, string $homeFallback = 'home'): string
  {
    if ($slug === null) {
      return $homeFallback;
    }

    $slug = urldecode($slug);

    // Handle "/" or empty
    if ($slug === '' || $slug === '/') {
      return $homeFallback;
    }

    // Trim leading/trailing slashes and whitespace
    $slug = trim($slug, "/ \t\n\r\0\x0B");

    // If it becomes empty after trim, still fallback
    if ($slug === '') {
      return $homeFallback;
    }

    return $slug;
  }
}
