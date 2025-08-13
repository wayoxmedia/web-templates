<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * ------------------------------------------------------------
 * Global Helpers for Laravel projects
 * ------------------------------------------------------------
 * - Keep this file framework-agnostic and portable across apps.
 * - Register it via composer.json -> autoload -> files.
 */

/**
 * Sanitize HTML, allows only a set of safe tags.
 * Tags allowed -> ['br', 'strong', 'em', 'p', 'ul', 'li', 'a']
 *
 * @param string $html The HTML string to sanitize.
 * @param array $allowed Allowed tag names (without attributes).
 * @return string          Sanitized HTML.
 */
if (!function_exists('safe_html')) {
  function safe_html(string $html, array $allowed = ['br', 'strong', 'em', 'p', 'ul', 'li', 'a']): string
  {
    $allowedTags = '';
    foreach ($allowed as $tag) {
      $allowedTags .= "<{$tag}>";
    }

    return strip_tags($html, $allowedTags);
  }
}

/**
 * Format a monetary amount with custom separators and symbol position.
 *
 * @param float|int $amount          The numeric amount to format.
 * @param string    $symbol          Currency symbol (e.g. $, €, Bs).
 * @param int       $decimals        Number of decimal places.
 * @param string    $decimalSep      Decimal separator.
 * @param string    $thousandsSep    Thousands separator.
 * @param bool      $symbolFirst     Whether symbol goes before or after amount.
 * @param bool      $spaceBetween    Whether to add space between symbol and amount.
 * @return string
 */
if (!function_exists('format_currency')) {
  function format_currency(
    float|int $amount,
    string $symbol = '$',
    int $decimals = 2,
    string $decimalSep = '.',
    string $thousandsSep = ',',
    bool $symbolFirst = true,
    bool $spaceBetween = false
  ): string {
    $formatted = number_format((float)$amount, $decimals, $decimalSep, $thousandsSep);
    $space = $spaceBetween ? ' ' : '';

    return $symbolFirst
      ? $symbol . $space . $formatted
      : $formatted . $space . $symbol;
  }
}

/**
 * Format a date to a human-readable format.
 */
if (!function_exists('format_date')) {
  function format_date($date, string $format = 'd/m/Y H:i'): string
  {
    return Carbon::parse($date)->format($format);
  }
}

/**
 * Get the application version from config or fallback.
 *
 * @return string
 */
if (!function_exists('app_version')) {
  function app_version(): string
  {
    return config('app.version', '1.0.0');
  }
}

/**
 * Retrieve a setting value from DB (if model exists) or fallback to config/constants.
 *
 * @param string $key
 * @param mixed  $default
 * @return mixed
 */
if (!function_exists('setting')) {
  function setting(string $key, mixed $default = null): mixed
  {
    // If you have a Settings model, try DB first
    if (class_exists(Setting::class)) {
      $value = Setting::query()->where('key', $key)->value('value');
      if ($value !== null) {
        return $value;
      }
    }
    // Fallback to config/constants.php
    return config("constants.$key", $default);
  }
}

/**
 * Quick debug log to storage/logs/laravel.log with context.
 *
 * @param mixed $message The message you want to read
 * @param array $context The response context, like ['user_id' => 123]
 *                       or ['order_id' => 456, 'status' => 'completed']
 *
 * @return void
 */
if (!function_exists('debug_log')) {
  function debug_log(mixed $message, array $context = []): void
  {
    // Normalize non-string messages
    if (!is_string($message)) {
      $message = print_r($message, true);
    }
    Log::debug($message, $context);
  }
}

/**
 * Pretty-print variables to the browser without halting execution.
 * - Colors and formatting vary by type for better readability.
 * - Safe for quick inspections in dev environments.
 *
 * @param mixed ...$vars
 * @return void
 */
if (!function_exists('debug_dump')) {
  function debug_dump(...$vars): void
  {
    echo "<div style='margin:10px 0;font-family:Menlo,Consolas,monospace;'>";
    foreach ($vars as $var) {
      $type = gettype($var);

      // Determine styling based on type
      // Fallbacks
      // $bg   = '#111';
      // $fg   = '#eee';
      // $bd   = '#444';
      // $labelColor = '#0ff';

      switch ($type) {
        case 'array':
          $bg = '#0b1e07'; // dark green
          $fg = '#b6ffb5';
          $bd = '#1c4420';
          $labelColor = '#7dff8a';
          break;
        case 'object':
          $bg = '#061b2b'; // dark blue
          $fg = '#b3e5ff';
          $bd = '#1b4e6f';
          $labelColor = '#7ad0ff';
          break;
        case 'integer':
        case 'double': // float
          $bg = '#2b0a0a'; // dark red
          $fg = '#ffb3b3';
          $bd = '#5b1c1c';
          $labelColor = '#ff7d7d';
          break;
        case 'boolean':
          $bg = '#2b2606'; // dark yellow
          $fg = '#ffe9a6';
          $bd = '#665b1a';
          $labelColor = '#ffd24d';
          break;
        case 'NULL':
          $bg = '#1f1f1f';
          $fg = '#cfcfcf';
          $bd = '#3b3b3b';
          $labelColor = '#9e9e9e';
          break;
        default:
          // string or other scalars
          $bg = '#111';
          $fg = '#e6e6e6';
          $bd = '#444';
          $labelColor = '#9bf6ff';
      }

      echo "<div style='
                background:{$bg};
                color:{$fg};
                border:1px solid {$bd};
                border-radius:6px;
                padding:10px;
                overflow:auto;'>";
      echo "<div style='
                font-weight:bold;
                color:{$labelColor};
                margin-bottom:6px;'>
                Type: {$type}
            </div>";

      echo "<pre style='margin:0;white-space:pre-wrap;word-break:break-word;'>";
      // Safer printing
      if (is_bool($var)) {
        echo $var ? 'true' : 'false';
      } elseif (is_null($var)) {
        echo 'NULL';
      } elseif (is_scalar($var)) {
        echo htmlspecialchars((string)$var, ENT_QUOTES, 'UTF-8');
      } else {
        // Arrays/Objects: pretty print
        echo htmlspecialchars(
          print_r($var, true),
          ENT_QUOTES,
          'UTF-8'
        );
      }
      echo "</pre>";
      echo "</div>";
    }
    echo "</div>";
  }
}
