<?php

namespace App\Exceptions;

use App\Support\SiteContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use RuntimeException;
use Throwable;

/**
 * Exception for backend service errors. !!!NOT IN USE!!!
 *
 * This exception is used to handle errors related to backend service availability,
 * connection issues, or HTTP request failures.
 */
class BackendServiceException extends RuntimeException {
  /**
   * Constructor for BackendServiceException.
   * Initializes the exception with a default message and optional code and previous exception.
   * @param string $message
   * @param int $code
   * @param Throwable|null $previous
   */
  public function __construct(
    string     $message = 'Backend service unavailable.',
    int        $code = 0,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }

  /**
   * Creates a new instance for service unavailability.
   * This is used when the backend service is not reachable or down.
   *
   * @param Throwable|null $previous
   * @return self
   */
  public static function connection(Throwable $previous = null): self {
    return new self('Backend connection failed.', 0, $previous);
  }

  /**
   * Creates a new instance for HTTP errors.
   * This is used when the backend service returns an HTTP error status.
   * It does not expose the exact status or body to the end user for security reasons.
   *
   * @param int $status
   * @param Throwable|null $previous
   * @return self
   */
  public static function http(int $status, Throwable $previous = null): self {
    return new self('Backend request failed.', $status, $previous);
  }

  /**
   * Renders the exception response.
   * This method generates a response based on the request type (JSON or HTML).
   *
   * @param Request $request
   * @return JsonResponse|Response
   */
  public function render(Request $request): Response|JsonResponse {
    // JSON (fetch/AJAX)
    if ($request->expectsJson()) {
      return response()->json([
        'message' => 'Service temporarily unavailable. Please try again.',
      ], 503);
    }

    // HTML themed by template if we have a site context
    $slug = SiteContext::templateSlug();

    $candidates = $slug
      ? ["templates.$slug.error.backend-unavailable", "error.backend-unavailable"]
      : ["error.backend-unavailable"];

    $view = collect($candidates)->first(fn($v) => View::exists($v)) ?? "errors.backend-unavailable";

    $layout = ($slug && View::exists("templates.$slug.layouts.app"))
      ? "templates.$slug.layouts.app"
      : "templates.default.layouts.app";

    return response()->view($view, [
      'message' => 'We are experiencing issues contacting the backend. Please try again in a moment.',
      'layout' => $layout,
    ], 503);
  }
}
