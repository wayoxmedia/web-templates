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
    'base_url'          => env('BACKEND_BASE_URL', 'http://mystorepanel/api'),
    'timeout'           => (int) env('BACKEND_TIMEOUT', 8),           // seconds
    'connect_timeout'   => (int) env('BACKEND_CONNECT_TIMEOUT', 3),   // seconds
    'retry'             => (int) env('BACKEND_RETRY', 1),             // attempts
    'retry_delay_ms'    => (int) env('BACKEND_RETRY_DELAY_MS', 150),  // milliseconds
  ],
];
