<?php

declare(strict_types=1);

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Laranex\NextLaravel\NextLaravelServiceProvider;
use Laranex\NextLaravel\Tests\Concerns\TouchesSharedSkeleton;
use Laranex\NextLaravel\Tests\TestCase;

/*
 * Every test that writes to or scans the skeleton's routes/ or
 * resources/stubs/vendor directories lives here and holds an exclusive lock.
 */
uses(TouchesSharedSkeleton::class);

it('merges the default configuration', function () {
    TestCase::$enableRoutes = true;
    $this->rebootApplication();

    expect(config('next-laravel.enable_routes'))->toBeTrue()
        ->and(config('next-laravel.web_routes_prefix'))->toBe('')
        ->and(config('next-laravel.api_routes_prefix'))->toBe('api');
});

it('registers the route files under routes/web and routes/api at boot', function () {
    TestCase::$enableRoutes = true;
    TestCase::$withRouteFixtures = true;
    $this->rebootApplication();

    expect(Route::has('fixtures.web.posts'))->toBeTrue()
        ->and(Route::has('fixtures.api.posts'))->toBeTrue();

    $this->get('/posts')->assertOk()->assertSee('web posts');
    $this->getJson('/api/posts')->assertOk()->assertExactJson(['posts' => []]);

    $webRoute = Route::getRoutes()->getByName('fixtures.web.posts');
    $apiRoute = Route::getRoutes()->getByName('fixtures.api.posts');

    expect($webRoute?->gatherMiddleware())->toBe(['web'])
        ->and($apiRoute?->gatherMiddleware())->toBe(['api']);
});

it('applies the configured route prefixes', function () {
    TestCase::$enableRoutes = true;
    TestCase::$withRouteFixtures = true;
    TestCase::$config = ['next-laravel.web_routes_prefix' => 'site', 'next-laravel.api_routes_prefix' => 'v1'];
    $this->rebootApplication();

    $this->get('/site/posts')->assertOk();
    $this->getJson('/v1/posts')->assertOk();
    $this->get('/posts')->assertNotFound();
});

it('does not register routes when disabled', function () {
    TestCase::$withRouteFixtures = true;
    TestCase::$config = ['next-laravel.enable_routes' => false];
    $this->rebootApplication();

    expect(Route::has('fixtures.web.posts'))->toBeFalse()
        ->and(Route::has('fixtures.api.posts'))->toBeFalse();
});

it('can register the route files on demand', function () {
    $this->writeFile(base_path('routes/web/on-demand.php'), "<?php\n\nIlluminate\\Support\\Facades\\Route::get('on-demand', fn () => 'ok')->name('fixtures.on-demand');");

    expect(Route::has('fixtures.on-demand'))->toBeFalse();

    $provider = $this->app->getProvider(NextLaravelServiceProvider::class);
    expect($provider)->toBeInstanceOf(NextLaravelServiceProvider::class);
    $provider->registerRoutes();

    // Fluent route names are indexed by Laravel's RouteServiceProvider once the application has booted.
    expect(Route::has('fixtures.on-demand'))->toBeFalse();

    $this->app->register(RouteServiceProvider::class, true);

    expect(Route::has('fixtures.on-demand'))->toBeTrue()
        ->and(route('fixtures.on-demand'))->toBe(url('on-demand'));

    $this->get('/on-demand')->assertOk()->assertSee('ok');
});

it('resolves the named routes of the registered route files with route()', function () {
    TestCase::$enableRoutes = true;
    TestCase::$withRouteFixtures = true;
    $this->rebootApplication();

    expect(route('fixtures.web.posts'))->toBe(url('posts'))
        ->and(route('fixtures.api.posts'))->toBe(url('api/posts'));
});

it('generates a web route file', function () {
    TestCase::$enableRoutes = true;
    $this->rebootApplication();
    $this->cleanup(base_path('routes/web/comments.php'));

    $this->artisan('next:route', ['route' => 'comment'])
        ->expectsOutputToContain('routes/web/comments.php has been successfully generated!')
        ->doesntExpectOutputToContain('enable_routes has been disabled')
        ->assertExitCode(0);

    expect(file_get_contents(base_path('routes/web/comments.php')))
        ->toContain("Route::prefix('comments')->group(function () {")
        ->toContain("// Route::get('/', [YourController::class, 'index'])->name('comments.index');")
        ->not->toContain('view(');
});

it('generates a route file that Laravel can load without registering a public route', function () {
    $this->cleanup(base_path('routes/web/galleries.php'));

    $this->artisan('next:route', ['route' => 'gallery'])->assertExitCode(0);

    $routes = count(Route::getRoutes()->getRoutes());

    Route::middleware('web')->group(base_path('routes/web/galleries.php'));

    expect(Route::getRoutes()->getRoutes())->toHaveCount($routes);

    $this->get('/galleries')->assertNotFound();
});

it('generates an api route file inside a version directory', function () {
    $this->cleanup(base_path('routes/api/v1'));

    $this->artisan('next:route', ['route' => 'tag', 'versionOrDirectory' => 'V1', '--api' => true])
        ->expectsOutputToContain('routes/api/v1/tags.php has been successfully generated!')
        ->assertExitCode(0);

    expect(file_get_contents(base_path('routes/api/v1/tags.php')))
        ->toContain("Route::prefix('v1/tags')->group(function () {");
});

it('fails when the route file already exists and overwrites it with --force', function () {
    $this->writeFile(base_path('routes/web/comments.php'), 'original');

    $this->artisan('next:route', ['route' => 'comments'])
        ->expectsOutputToContain('routes/web/comments.php already exists!')
        ->assertExitCode(1);

    expect(file_get_contents(base_path('routes/web/comments.php')))->toBe('original');

    $this->artisan('next:route', ['route' => 'comments', '--force' => true])->assertExitCode(0);

    expect(file_get_contents(base_path('routes/web/comments.php')))->toContain("Route::prefix('comments')");
});

it('warns when route registration is disabled', function () {
    $this->cleanup(base_path('routes/web/comments.php'));

    $this->artisan('next:route', ['route' => 'comment'])
        ->expectsOutputToContain('next-laravel.enable_routes has been disabled')
        ->assertExitCode(0);
});

it('prefers stubs published to resources/stubs/vendor/next-laravel', function () {
    $this->cleanup(resource_path('stubs/vendor/next-laravel'));
    $this->cleanup(base_path('routes/web/comments.php'));
    $this->writeFile(resource_path('stubs/vendor/next-laravel/route.php.stub'), '<?php // custom {{route}} {{versionOrDirectory}} {{prefix}}');

    $this->artisan('next:route', ['route' => 'comment'])->assertExitCode(0);

    expect(file_get_contents(base_path('routes/web/comments.php')))->toBe('<?php // custom comments  comments');
});

it('keeps filling the legacy versionOrDirectory placeholder of previously published stubs', function () {
    $this->cleanup(resource_path('stubs/vendor/next-laravel'));
    $this->cleanup(base_path('routes/api/v1'));
    $this->writeFile(resource_path('stubs/vendor/next-laravel/route.php.stub'), "<?php // '{{versionOrDirectory}}/{{route}}'");

    $this->artisan('next:route', ['route' => 'tag', 'versionOrDirectory' => 'v1', '--api' => true])->assertExitCode(0);

    expect(file_get_contents(base_path('routes/api/v1/tags.php')))->toBe("<?php // '/v1/tags'");
});

it('treats a missing enable_routes key as enabled', function () {
    $this->cleanup(base_path('routes/web/comments.php'));
    config()->set('next-laravel', ['web_routes_prefix' => '', 'api_routes_prefix' => 'api']);

    $this->artisan('next:route', ['route' => 'comment'])
        ->doesntExpectOutputToContain('enable_routes has been disabled')
        ->assertExitCode(0);
});

it('rejects a nested route name', function (string $route) {
    $this->artisan('next:route', ['route' => $route])
        ->expectsOutputToContain("The route name [$route] must not contain \"/\" or \"\\\". Nested names are not supported.")
        ->assertExitCode(1);

    expect(base_path('routes/web/admin/users.php'))->not->toBeFile();
})->with(['admin/users', 'admin\\users']);

it('accepts nested route directories made of plain segments', function () {
    $this->cleanup(base_path('routes/api/admin'));

    $this->artisan('next:route', ['route' => 'user', 'versionOrDirectory' => '/Admin/v1_beta-2/', '--api' => true])->assertExitCode(0);

    expect(file_get_contents(base_path('routes/api/admin/v1_beta-2/users.php')))->toContain("Route::prefix('admin/v1_beta-2/users')");
});
