<?php

declare(strict_types=1);

use Laranex\NextLaravel\Decorator;

it('strips the base path from generated file paths', function () {
    expect(Decorator::getRelativePath(base_path('app/Modules/BlogModule/Features/CreatePostFeature.php')))
        ->toBe('app/Modules/BlogModule/Features/CreatePostFeature.php')
        ->and(Decorator::getRelativePath('/elsewhere/file.php'))->toBe('elsewhere/file.php');
});

it('decorates the generated, error and disabled-routes messages', function () {
    expect(Decorator::getFileGeneratedOutput(base_path('routes/web/posts.php')))
        ->toBe('🚀🚀🚀 [routes/web/posts.php has been successfully generated!] 🚀🚀🚀')
        ->and(Decorator::getFileGenerationErrorOutput('boom'))->toBe('🚀🚀🚀 [boom] 🚀🚀🚀')
        ->and(Decorator::getDisableRoutesWarning())->toContain('next-laravel.enable_routes has been disabled');
});
