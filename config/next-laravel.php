<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Route Registration
    |--------------------------------------------------------------------------
    |
    | When enabled, every PHP file under routes/web and routes/api is loaded
    | by the service provider with the "web" and "api" middleware groups.
    |
    */

    'enable_routes' => env('NEXT_LARAVEL_ENABLE_ROUTES', true),

    /*
    |--------------------------------------------------------------------------
    | Route Prefixes
    |--------------------------------------------------------------------------
    |
    | The URI prefixes applied to the route files under routes/web and
    | routes/api respectively.
    |
    */

    'web_routes_prefix' => env('NEXT_LARAVEL_WEB_ROUTES_PREFIX', ''),

    'api_routes_prefix' => env('NEXT_LARAVEL_API_ROUTES_PREFIX', 'api'),
];
