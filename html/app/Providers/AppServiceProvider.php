<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
  /**
   * Register any application services.
   */
  public function register(): void {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void {
    /**
     * Usage:
     * @role('admin')
     *   ...visible for admin...
     * @endrole
     *
     * @role('admin,editor')  // any-match
     *   ...visible for admin OR editor...
     * @endrole
     */
    Blade::if('role', function (string $roles): bool {
      $userRoles = array_map('strval', (array)session('auth.roles', []));

      $required = preg_split('/[|,]/', $roles, -1, PREG_SPLIT_NO_EMPTY) ?: [];
      $required = array_values(array_unique(array_map('trim', $required)));
      if (empty($required)) {
        return true; // no roles specified → do not hide content
      }

      return count(array_intersect($required, $userRoles)) > 0;
    });
  }
}
