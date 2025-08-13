<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Backend API configuration
  |--------------------------------------------------------------------------
  */
  'base_url' => env('API_BASE_URL'),
  'timeout'  => (int) env('API_TIMEOUT', 8),
  'token'    => env('API_TOKEN', null),
  'cache_ttl'=> (int) env('API_CACHE_TTL', 600), // seconds

  // Optional: add a service-to-service header to identify this app
  'default_headers' => [
    'Accept'        => 'application/json',
    'X-Client-App'  => 'template-frontend',
  ],
];
