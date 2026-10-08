<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Laranex\NextLaravel\NextLaravelServiceProvider;
use Laranex\NextLaravel\Tests\TestCase;

$packageRoot = dirname(__DIR__, 2);

// The provider builds its publish paths from its own __DIR__, which uses the native directory separator.
$sourceRoot = dirname((string) (new ReflectionClass(NextLaravelServiceProvider::class))->getFileName());

it('lets the host application override the configuration', function () {
    TestCase::$config = ['next-laravel.api_routes_prefix' => 'v2'];
    $this->rebootApplication();

    expect(config('next-laravel.api_routes_prefix'))->toBe('v2')
        ->and(config('next-laravel.web_routes_prefix'))->toBe('');
});

it('registers the package views', function () {
    expect(view('next-laravel::welcome')->render())->toContain('<title>Next Laravel</title>');
});

it('registers every generator command', function () {
    expect(array_keys(Artisan::all()))->toContain(
        'next:route',
        'next:controller',
        'next:request',
        'next:feature',
        'next:operation',
        'next:job',
    );
});

it('exposes publish tags for the config, views and stubs', function () use ($packageRoot, $sourceRoot) {
    expect(ServiceProvider::pathsToPublish(NextLaravelServiceProvider::class, 'next-laravel-config'))
        ->toBe([$sourceRoot.'/../config/next-laravel.php' => config_path('next-laravel.php')])
        ->and(ServiceProvider::pathsToPublish(NextLaravelServiceProvider::class, 'next-laravel-views'))
        ->toBe([$sourceRoot.'/../resources/views' => resource_path('views/vendor/next-laravel')])
        ->and(ServiceProvider::pathsToPublish(NextLaravelServiceProvider::class, 'next-laravel-stubs'))
        ->toBe([$sourceRoot.'/../resources/stubs' => resource_path('stubs/vendor/next-laravel')])
        ->and(ServiceProvider::pathsToPublish(NextLaravelServiceProvider::class, 'next-laravel'))
        ->toHaveCount(3)
        ->and(glob($packageRoot.'/resources/stubs/*.stub'))->toHaveCount(7)
        ->and(file_get_contents($packageRoot.'/config/next-laravel.php'))->toContain("'enable_routes'");
});
