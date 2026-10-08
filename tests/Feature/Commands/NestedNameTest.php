<?php

declare(strict_types=1);

it('rejects names containing a path separator', function (string $command, array $arguments, string $type, string $name) {
    $this->artisan($command, $arguments)
        ->expectsOutputToContain("The $type name [$name] must not contain \"/\" or \"\\\". Nested names are not supported.")
        ->assertExitCode(1);
})->with([
    'feature' => ['next:feature', ['feature' => 'Blog/createPost', 'module' => 'Blog'], 'feature', 'Blog/createPost'],
    'feature with a backslash' => ['next:feature', ['feature' => 'Blog\\CreatePost', 'module' => 'Blog'], 'feature', 'Blog\\CreatePost'],
    'feature module' => ['next:feature', ['feature' => 'createPost', 'module' => 'Admin/Blog'], 'module', 'Admin/Blog'],
    'controller' => ['next:controller', ['controller' => 'Admin/Post', 'module' => 'Blog'], 'controller', 'Admin/Post'],
    'controller module' => ['next:controller', ['controller' => 'Post', 'module' => 'Admin\\Blog'], 'module', 'Admin\\Blog'],
    'operation' => ['next:operation', ['operation' => 'Blog/slugify', 'module' => 'Blog'], 'operation', 'Blog/slugify'],
    'operation module' => ['next:operation', ['operation' => 'slugify', 'module' => 'Admin/Blog'], 'module', 'Admin/Blog'],
    'job' => ['next:job', ['job' => 'Mail/send', 'module' => 'Blog'], 'job', 'Mail/send'],
    'job module' => ['next:job', ['job' => 'send', 'module' => 'Admin/Blog'], 'module', 'Admin/Blog'],
    'request' => ['next:request', ['request' => 'Admin/StorePost', 'module' => 'Blog'], 'request', 'Admin/StorePost'],
    'request module' => ['next:request', ['request' => 'StorePost', 'module' => 'Admin\\Blog'], 'module', 'Admin\\Blog'],
]);
