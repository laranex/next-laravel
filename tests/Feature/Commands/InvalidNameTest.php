<?php

declare(strict_types=1);

use Laranex\NextLaravel\Generators\RouteGenerator;

it('rejects names that do not produce a valid PHP identifier', function (string $command, array $arguments, string $type, string $name, string $identifier) {
    $this->artisan($command, $arguments)
        ->expectsOutputToContain("The $type name [$name] is invalid: [$identifier] is not a valid PHP identifier. Use only letters, numbers and underscores, starting with a letter or an underscore.")
        ->assertExitCode(1);
})->with([
    'feature' => ['next:feature', ['feature' => 'A;system(1)', 'module' => 'Blog'], 'feature', 'A;system(1)', 'A;system(1)Feature'],
    'feature module' => ['next:feature', ['feature' => 'createPost', 'module' => '..'], 'module', '..', '..Module'],
    'controller' => ['next:controller', ['controller' => 'Post.php', 'module' => 'Blog'], 'controller', 'Post.php', 'Post.phpController'],
    'controller module' => ['next:controller', ['controller' => 'Post', 'module' => '1st'], 'module', '1st', '1stModule'],
    'operation' => ['next:operation', ['operation' => "x'];", 'module' => 'Blog'], 'operation', "x'];", "X'];Operation"],
    'operation module' => ['next:operation', ['operation' => 'slugify', 'module' => 'Blog;'], 'module', 'Blog;', 'Blog;Module'],
    'job' => ['next:job', ['job' => 'A;system(1)', 'module' => 'Blog'], 'job', 'A;system(1)', 'A;system(1)Job'],
    'job module' => ['next:job', ['job' => 'send', 'module' => '..'], 'module', '..', '..Module'],
    'request' => ['next:request', ['request' => 'Store-Post!', 'module' => 'Blog'], 'request', 'Store-Post!', 'StorePost!Request'],
    'request module' => ['next:request', ['request' => 'StorePost', 'module' => '..'], 'module', '..', '..Module'],
]);

it('rejects route names that would break out of the generated route file', function (string $route, string $generated) {
    $this->artisan('next:route', ['route' => $route])
        ->expectsOutputToContain("The route name [$route] is invalid: [$generated] may only contain lowercase letters, numbers, \"-\" and \"_\".")
        ->assertExitCode(1);
})->with([
    'quote' => ["x'];", "x'];"],
    'dots' => ['..', '..'],
    'extension' => ['post.php', 'post.phps'],
]);

it('rejects a versionOrDirectory that is not a plain relative path', function (string $directory) {
    $this->artisan('next:route', ['route' => 'post', 'versionOrDirectory' => $directory])
        ->expectsOutputToContain("The versionOrDirectory [$directory] is invalid: use \"/\"-separated segments of lowercase letters, numbers, \"-\" and \"_\" only.")
        ->assertExitCode(1);

    expect(base_path('posts.php'))->not->toBeFile()
        ->and(base_path('routes/posts.php'))->not->toBeFile();
})->with(['..', '../..', 'v1/../..', 'v1\\..', 'admin\\v1', 'v1//admin', 'v1.0', "v1'"]);

it('only generates web or api route files', function () {
    expect(fn () => (new RouteGenerator)->generate('post', '', '../../app'))
        ->toThrow(InvalidArgumentException::class, 'The route file type [../../app] is invalid: use "web" or "api".');

    expect(base_path('app/posts.php'))->not->toBeFile();
});
