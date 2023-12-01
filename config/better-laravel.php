<?php

return [

    /**
     * Register routes under routes/web and routes/api by the service provider or not.
     */
    'enable_routes' => env('next_LARAVEL_ENABLE_ROUTES', true),

    /**
     * Prefix for web routes
     */
    'web_routes_prefix' => env('next_LARAVEL_WEB_ROUTES_PREFIX', ''),

    /**
     * Prefix for api routes
     */
    'api_routes_prefix' => env('next_LARAVEL_API_ROUTES_PREFIX', 'api'),
];
