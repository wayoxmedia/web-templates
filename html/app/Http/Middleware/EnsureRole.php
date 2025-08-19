<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole {
  private const SESSION_BACKEND_ROLES = 'auth.roles';

  /**
   * Require that the current session has at least one of the given roles.
   * Usage in routes: ->middleware('ensure.role:admin') or ('ensure.role:admin,editor') or ('ensure.role:admin|editor')
   */
  public function handle(Request $request, Closure $next, ...$roleArgs): Response {
    // If no roles specified, let it pass (avoid accidental lockouts)
    if (empty($roleArgs)) {
      return $next($request);
    }

    // Normalize required roles from comma/pipe separated parameters
    $required = [];
    foreach ($roleArgs as $arg) {
      $parts = preg_split('/[|,]/', (string)$arg, -1, PREG_SPLIT_NO_EMPTY);
      foreach ($parts as $p) {
        $required[] = trim($p);
      }
    }
    $required = array_values(array_unique(array_filter($required)));

    $userRoles = array_map('strval', (array)Session::get(self::SESSION_BACKEND_ROLES, []));

    // ANY-match strategy: user must have at least one of the required roles
    $hasAny = count(array_intersect($required, $userRoles)) > 0;

    if (!$hasAny) {
      // For web requests, return 403. If the client expects JSON, Laravel will format accordingly.
      abort(403, 'Forbidden');
    }

    return $next($request);
  }
}
