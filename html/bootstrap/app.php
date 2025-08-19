<?php

use App\Http\Middleware\EnsureJwt;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\ResolveSiteMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    // api: __DIR__.'/../routes/api.php', // uncomment to enable API routes
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
      // Our alias used in routes/web.php
      'resolve.site' => ResolveSiteMiddleware::class,
      'ensure.jwt' => EnsureJwt::class,
      'ensure.role' => EnsureRole::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    //
  })->create();
