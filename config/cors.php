<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register',
        'forgot-password',
        'reset-password',
        'dashboard'
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], // Better than wildcard

    'allowed_origins' => [
        'https://sizlandfrontend.vercel.app/login',
        'https://sizlandfrontend.vercel.app',
        'http://localhost:5000', // For local development
    ],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'Accept',
    ],

    'exposed_headers' => [
        'X-CSRF-TOKEN', // Crucial for Sanctum
        'Authorization',
    ],

    'max_age' => 60 * 60 * 2, // 2 hour preflight cache

    'supports_credentials' => true, // Keep true for Sanctum
];
