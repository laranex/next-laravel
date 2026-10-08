<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Tests;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Laranex\NextLaravel\NextLaravelServiceProvider;
use Laranex\NextLaravel\Tests\Concerns\TouchesSharedSkeleton;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Config values applied before the package boots (simulates host-app config).
     *
     * @var array<string, mixed>
     */
    public static array $config = [];

    /**
     * Copy the fixture route files into the skeleton before the package boots.
     */
    public static bool $withRouteFixtures = false;

    /**
     * Route discovery scans the shared skeleton, so tests opt in to it explicitly.
     */
    public static bool $enableRoutes = false;

    /**
     * Files and directories created by the test, removed on tear down.
     *
     * @var list<string>
     */
    protected array $createdPaths = [];

    /**
     * @var resource|null
     */
    private $skeletonLock = null;

    protected function setUp(): void
    {
        $this->skeletonLock = fopen(sys_get_temp_dir().'/next-laravel-tests.lock', 'c') ?: null;

        if ($this->skeletonLock !== null) {
            flock($this->skeletonLock, $this->touchesSharedSkeleton() ? LOCK_EX : LOCK_SH);
        }

        parent::setUp();
    }

    private function touchesSharedSkeleton(): bool
    {
        return in_array(TouchesSharedSkeleton::class, class_uses_recursive($this), true);
    }

    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            NextLaravelServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('k', 32)));

        if (! static::$enableRoutes) {
            $app['config']->set('next-laravel.enable_routes', false);
        }

        foreach (static::$config as $key => $value) {
            $app['config']->set($key, $value);
        }

        if (static::$withRouteFixtures) {
            $this->copyRouteFixtures($app);
        }
    }

    protected function tearDown(): void
    {
        static::$config = [];
        static::$withRouteFixtures = false;
        static::$enableRoutes = false;

        $files = new Filesystem;

        foreach (array_reverse($this->createdPaths) as $path) {
            is_dir($path) ? $files->deleteDirectory($path) : $files->delete($path);
        }

        if ($this->touchesSharedSkeleton()) {
            foreach ([base_path('routes/web'), base_path('routes/api')] as $directory) {
                if (is_dir($directory) && count((array) scandir($directory)) === 2) {
                    rmdir($directory);
                }
            }
        }

        parent::tearDown();

        if ($this->skeletonLock !== null) {
            flock($this->skeletonLock, LOCK_UN);
            fclose($this->skeletonLock);
            $this->skeletonLock = null;
        }
    }

    /**
     * Re-create the application so defineEnvironment() runs again with the current static overrides.
     */
    public function rebootApplication(): void
    {
        $this->refreshApplication();
    }

    /**
     * Register a path to delete on tear down.
     */
    public function cleanup(string $path): string
    {
        $this->createdPaths[] = $path;

        return $path;
    }

    /**
     * Write a file (creating its directory) and register it for cleanup.
     */
    public function writeFile(string $path, string $contents): string
    {
        $directory = dirname($path);

        if (! is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }

        file_put_contents($path, $contents);

        return $this->cleanup($path);
    }

    private function copyRouteFixtures(Application $app): void
    {
        foreach (['web', 'api'] as $type) {
            $this->writeFile(
                $app->basePath("routes/$type/posts.php"),
                (string) file_get_contents(__DIR__."/Fixtures/routes/$type/posts.php"),
            );
        }
    }
}
