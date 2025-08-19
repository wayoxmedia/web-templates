<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Third Party Services
  |--------------------------------------------------------------------------
  |
  | This file is for storing the credentials for third party services such
  | as Mailgun, Postmark, AWS and more. This file provides the de facto
  | location for this type of information, allowing packages to have
  | a conventional file to locate the various service credentials.
  |
  */

  'postmark' => [
    'token' => env('POSTMARK_TOKEN'),
  ],

  'resend' => [
    'key' => env('RESEND_KEY'),
  ],

  'ses' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  ],

  'slack' => [
    'notifications' => [
      'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
      'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
    ],
  ],

  'site_resolver' => [
    'url' => env('SITE_RESOLVER_URL'),
  ],

  'backend' => [
    // Backend headless base
    'base_url' => env('BACKEND_BASE_URL', 'http://mystorepanel.test'),
    // Backend headless API prefix
    'api_prefix' => env('BACKEND_API_PREFIX', '/api'),
    // Shared secret for S2S (server-to-server) communication
    'service_token' => env('BACKEND_SERVICE_TOKEN', 'replace-with-a-long-random-string'),

    // Timeouts & retries
    'timeout' => (int)env('BACKEND_TIMEOUT', 10),          // secs
    'connect_timeout' => (int)env('BACKEND_CONNECT_TIMEOUT', 5),   // secs
    'retry' => (int)env('BACKEND_RETRY', 1),             // retries
    'retry_delay_ms' => (int)env('BACKEND_RETRY_DELAY_MS', 150),  // ms
  ],
];
