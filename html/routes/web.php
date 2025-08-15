<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/**
 *
 * |Web Routes
 * |-
 *
 * Define here any specific routes (admin, auth, etc.)
 * so they are not caught by the public site catch-all.
 *
 * |Notes
 * |-
 *
 * - Route order matters: define specific routes (admin, auth, public APIs) first, then the public site group
 *   with `resolve.site` and the catch-all route.
 * - The regex in `where()` prevents the catch-all from capturing common reserved paths.
 *   Adjust the list of excluded paths as needed.
 * - PageController@ show should accept the signature `show(?string $slug = null)`.
 * - The old `return view('pages.home')` approach is no longer used, since the view
 *   is now resolved dynamically from the theme namespace (`theme::...`) set by the middleware.
 *
 *| Web Admin Routes
 *|-
 *
 * Admin auth (server-side) and dashboard entry point.
 *
 * NOTE: We'll attach middleware in a later step (EnsureJwt / EnsureRole).
 */
Route::prefix('admin')->name('admin.')->group(function () {
  // Public: Login (GET form / POST submit)
  Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');
  Route::get('/forgot', [AuthController::class, 'showForgot'])
    ->name('forgot');
  // Submitting login via POST to avoid CSRF via link spoofing
  Route::post('/login', [AuthController::class, 'submitLogin'])
    ->name('login.submit');// ← name = admin.login.submit

  // Protected area (requires JWT auth).
  Route::middleware('ensure.jwt')->group(function () {
    // Logout (POST to prevent CSRF via link spoofing)
    Route::post('/logout', [AuthController::class, 'logout'])
      ->name('logout');

    // Dashboard (Admin only)
    Route::get('/', [DashboardController::class, 'index'])
      ->middleware('ensure.role:admin|editor') // Example roles, adjust as needed
      ->name('dashboard');

    // ---- Extra sections with role protection ----

    // Users (admin only)
    Route::get('/users', function () {
      return response('Users page (admin only) – placeholder', 200);
    })->middleware('ensure.role:admin')->name('users.index');

    // Content (admin OR editor)
    Route::get('/content', function () {
      return response('Content page (admin or editor) – placeholder', 200);
    })->middleware('ensure.role:admin,editor')->name('content.index');

    // Reports (admin OR manager)
    Route::get('/reports', function () {
      return response('Reports page (admin or manager) – placeholder', 200);
    })->middleware('ensure.role:admin|manager')->name('reports.index');
  });
});

/**
 * | Public site (multi-tenant + templates)
 * |-
 *
 * These routes should be placed LAST to act as the public site catch-all.
 *
 * The `resolve.site` middleware will:
 *   - Resolve the Site based on the domain (tenant + template)
 *   - Inject tenant/template into the service container
 *   - Register the theme view namespace ("theme::")
 */
Route::middleware('resolve.site')->group(function () {
  // Home "/" → handled by PageController@show with $slug = null
  Route::get('/', [PageController::class, 'show'])->name('home');

  // Catch-all route for public pages: /about, /contact, /products/x, etc.
  // This must be placed LAST within this group
  // and use a regex to avoid intercepting reserved routes.
  Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^(?!storage|templates|vendor|api|admin|login|logout|register).*$')
    ->name('page.show');
});
