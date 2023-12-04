<?php

namespace Laranex\NextLaravel;

use App;
use Illuminate\Support\Facades\Route;
use Laranex\NextLaravel\Commands\ControllerMakeCommand;
use Laranex\NextLaravel\Commands\FeatureMakeCommand;
use Laranex\NextLaravel\Commands\JobMakeCommand;
use Laranex\NextLaravel\Commands\OperationMakeCommand;
use Laranex\NextLaravel\Commands\RequestMakeCommand;
use Laranex\NextLaravel\Commands\RouteMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NextLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('next-laravel')
            ->hasConfigFile()
            ->hasCommands([
                RouteMakeCommand::class,
                ControllerMakeCommand::class,
                RequestMakeCommand::class,
                FeatureMakeCommand::class,
                OperationMakeCommand::class,
                JobMakeCommand::class,
            ])->hasViews('next-laravel');

        $packageShortName = $package->shortName();
        $this->publishes([
            __DIR__.'/../resources/stubs' => resource_path("stubs/vendor/$packageShortName"),
        ], "$packageShortName-stubs");

    }

    public function packageRegistered(): void
    {
        if (config('next-laravel.enable_routes') && ! App::routesAreCached()) {
            $this->registerRoutes();
        }
    }

    public function registerRoutes(): void
    {
        $webRoutes = NextLaravel::getAllFilesOfADirectory(base_path('routes/web'), 'php');
        $apiRoutes = NextLaravel::getAllFilesOfADirectory(base_path('routes/api'), 'php');

        $webRoutesPrefix = config('next-laravel.web_routes_prefix');
        $apiRoutesPrefix = config('next-laravel.api_routes_prefix');

        foreach ($webRoutes as $route) {
            Route::middleware('web')
                ->prefix($webRoutesPrefix)
                ->group($route);
        }

        foreach ($apiRoutes as $route) {
            Route::middleware('api')
                ->prefix($apiRoutesPrefix)
                ->group($route);
        }
    }
}
