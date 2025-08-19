<?php

namespace App\Http\Middleware;

use App\Services\BackendApi;
use App\Support\SiteContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to resolve the site based on the incoming request's domain.
 *
 * Using readonly property to ensure immutability after construction
 *
 * This middleware performs the following steps:
 * 1. Normalizes the host by stripping "www." if present.
 * 2. Calls the Backend API to resolve the site using the normalized domain.
 * 3. If the site is found, it retrieves tenant, template, site, and settings from Session.
 * 4. If the site cannot be resolved, it aborts with a 404 error.
 * 5. Injects the resolved tenant, template, site, and settings into the service container.
 * 6. Registers view namespaces for the active theme and a base fallback theme.
 * 7. Proceeds with the next middleware or request handler.
 * If the site cannot be resolved, it aborts with a 404 error.
 *
 * Visual flow:
 * [User]
 * - |
 * - v
 *
 * HTTP Request: GET http://template1.test/about
 * - |
 * - v
 *
 * Middleware ResolveSiteMiddleware
 * - |-> Reads domain: "template1.test"
 * - |-> Query API backend: /sites/resolve?domain=template1.test
 * - |-> Saves tenant, template, site, settings
 * - |-> Configure "theme::" and "themeBase::" namespaces
 * - v
 *
 * PageController->show($slug = 'about')
 * -> Ask page to backend
 * -> Render view from right template.
 *
 * - |
 * - v
 *
 * [User receive HTML with assigned template]
 */
readonly class ResolveSiteMiddleware {
  /**
   * Create a new middleware instance.
   *
   * @param BackendApi $api The backend API service to resolve sites.
   */
  public function __construct(private BackendApi $api) {}

  /**
   * Handle incoming request by resolving site via Backend API.
   * Steps:
   * - Normalize host (strip "www.")
   * - Call API /sites/resolve?domain={host}
   * - Inject tenant, template, site, settings into container
   * - Register view namespaces for the active theme and base fallback
   */
  public function handle(Request $request, Closure $next): Response {
    $host = strtolower($request->getHost());
    $domain = preg_replace('/^www\./i', '', $host) ?: $host;

    $ctx = SiteContext::remember($this->api, $domain, 600);

    $resolved = $ctx['data'] ?? $ctx;

    if (!$resolved ||
      !is_array($resolved) ||
      !isset($resolved['tenant'], $resolved['template'], $resolved['site'])
    ) {
      abort(404, 'Site not found for this domain.');
    }

    $tenant = $resolved['tenant'];
    $template = $resolved['template'];
    $settings = $resolved['settings'] ?? [];

    // Accept both shapes: "eglee" OR ["slug" => "eglee"]
    $templateSlug = is_array($template)
      ? ($template['slug'] ?? 'default')
      : (is_string($template) ? $template : 'default');

    // Expose data in the service container for later use
    app()->instance('tenant', (object)$tenant);
    app()->instance('template', (object)(is_array($template) ? $template : ['slug' => $templateSlug]));
    app()->instance('site', (object)$resolved['site']);
    app()->instance('theme_settings', $settings);

    // Register theme view namespaces
    $themePath = resource_path("views/templates/{$templateSlug}");
    $basePath = resource_path('views/templates/default');

    View::replaceNamespace('theme', [$themePath, $basePath]);
    View::replaceNamespace('themeBase', [$basePath]);

    view()->share([
      'tenant' => $tenant,
      'site' => $resolved['site'],
      'templateSlug' => $templateSlug,
      'themeSettings' => $settings,
    ]);

    return $next($request);
  }
}
