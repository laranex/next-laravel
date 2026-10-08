<?php

declare(strict_types=1);

namespace Laranex\NextLaravel;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laranex\NextLaravel\Commands\ControllerMakeCommand;
use Laranex\NextLaravel\Commands\FeatureMakeCommand;
use Laranex\NextLaravel\Commands\JobMakeCommand;
use Laranex\NextLaravel\Commands\OperationMakeCommand;
use Laranex\NextLaravel\Commands\RequestMakeCommand;
use Laranex\NextLaravel\Commands\RouteMakeCommand;

class NextLaravelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/next-laravel.php', 'next-laravel');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'next-laravel');

        if ($this->shouldRegisterRoutes()) {
            $this->registerRoutes();
        }

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            RouteMakeCommand::class,
            ControllerMakeCommand::class,
            RequestMakeCommand::class,
            FeatureMakeCommand::class,
            OperationMakeCommand::class,
            JobMakeCommand::class,
        ]);

        $this->publishes([
            __DIR__.'/../config/next-laravel.php' => config_path('next-laravel.php'),
        ], ['next-laravel', 'next-laravel-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/next-laravel'),
        ], ['next-laravel', 'next-laravel-views']);

        $this->publishes([
            __DIR__.'/../resources/stubs' => resource_path('stubs/vendor/next-laravel'),
        ], ['next-laravel', 'next-laravel-stubs']);
    }

    /**
     * Register every route file found under routes/web and routes/api.
     */
    public function registerRoutes(): void
    {
        $webRoutes = NextLaravel::getAllFilesOfADirectory($this->app->basePath('routes/web'), 'php');
        $apiRoutes = NextLaravel::getAllFilesOfADirectory($this->app->basePath('routes/api'), 'php');

        $webRoutesPrefix = $this->config('web_routes_prefix', '');
        $apiRoutesPrefix = $this->config('api_routes_prefix', 'api');

        foreach ($webRoutes as $route) {
            Route::middleware('web')
                ->prefix(is_string($webRoutesPrefix) ? $webRoutesPrefix : '')
                ->group($route);
        }

        foreach ($apiRoutes as $route) {
            Route::middleware('api')
                ->prefix(is_string($apiRoutesPrefix) ? $apiRoutesPrefix : 'api')
                ->group($route);
        }

        Route::getRoutes()->refreshNameLookups();
        Route::getRoutes()->refreshActionLookups();
    }

    /**
     * Routes are only registered when enabled and not already cached.
     */
    protected function shouldRegisterRoutes(): bool
    {
        if (! $this->config('enable_routes', true)) {
            return false;
        }

        return ! ($this->app instanceof CachesRoutes && $this->app->routesAreCached());
    }

    private function config(string $key, mixed $default = null): mixed
    {
        return $this->app->make(ConfigRepository::class)->get('next-laravel.'.$key, $default);
    }
}
