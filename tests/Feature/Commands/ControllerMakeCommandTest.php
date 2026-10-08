<?php

declare(strict_types=1);

beforeEach(fn () => $this->cleanup(app_path('Modules/BlogModule')));

it('generates a controller inside the module', function () {
    $this->artisan('next:controller', ['controller' => 'post', 'module' => 'blog'])
        ->expectsOutputToContain('Modules/BlogModule/Http/Controllers/PostController.php has been successfully generated!')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/BlogModule/Http/Controllers/PostController.php')))
        ->toContain('namespace App\Modules\BlogModule\Http\Controllers;')
        ->toContain('use Laranex\NextLaravel\Cores\Controller;')
        ->toContain('class PostController extends Controller');
});

it('fails when the controller already exists and overwrites it with --force', function () {
    $this->writeFile(app_path('Modules/BlogModule/Http/Controllers/PostController.php'), 'original');

    $this->artisan('next:controller', ['controller' => 'post', 'module' => 'blog'])
        ->expectsOutputToContain('PostController.php already exists!')
        ->assertExitCode(1);

    expect(file_get_contents(app_path('Modules/BlogModule/Http/Controllers/PostController.php')))->toBe('original');

    $this->artisan('next:controller', ['controller' => 'post', 'module' => 'blog', '--force' => true])->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/BlogModule/Http/Controllers/PostController.php')))->toContain('class PostController');
});
