<?php

declare(strict_types=1);

use Laranex\NextLaravel\Str;

it('formats route names as plural kebab-case', function (string $input, string $expected) {
    expect(Str::route($input))->toBe($expected);
})->with([
    ['post', 'posts'],
    ['BlogPost', 'blog-posts'],
    ['user profile', 'user-profiles'],
]);

it('formats directories in lower case', function () {
    expect(Str::directory('V1'))->toBe('v1')
        ->and(Str::directory(''))->toBe('');
});

it('extracts the real name of a class file', function () {
    expect(Str::realName('CreateArticleFeature.php', '/Feature.php/'))->toBe('Create Article')
        ->and(Str::realName('CreateArticle'))->toBe('Create Article');
});

it('formats feature names', function (string $input, string $expected) {
    expect(Str::feature($input))->toBe($expected);
})->with([
    ['create post', 'CreatePostFeature'],
    ['CreatePostFeature.php', 'CreatePostFeature'],
    ['createPost', 'CreatePostFeature'],
    ['CreatePostFeature', 'CreatePostFeature'],
    ['create', 'CreateFeature'],
]);

it('keeps the directory part of nested feature names', function () {
    expect(Str::feature('posts/createPost'))->toBe('Posts'.DIRECTORY_SEPARATOR.'CreatePostFeature');
});

it('formats job names', function (string $input, string $expected) {
    expect(Str::job($input))->toBe($expected);
})->with([
    ['send email', 'SendEmailJob'],
    ['SendEmailJob.php', 'SendEmailJob'],
    ['sendEmailJob', 'SendEmailJob'],
]);

it('formats operation names', function (string $input, string $expected) {
    expect(Str::operation($input))->toBe($expected);
})->with([
    ['create post', 'CreatePostOperation'],
    ['CreatePostOperation.php', 'CreatePostOperation'],
    ['createPost', 'CreatePostOperation'],
]);

it('formats module names', function (string $input, string $expected) {
    expect(Str::module($input))->toBe($expected);
})->with([
    ['blog', 'BlogModule'],
    ['blog module', 'BlogModule'],
    ['BlogModule', 'BlogModule'],
    ['user-profile', 'UserProfileModule'],
]);

it('formats controller names', function (string $input, string $expected) {
    expect(Str::controller($input))->toBe($expected);
})->with([
    ['post', 'PostController'],
    ['PostController.php', 'PostController'],
    ['postController', 'PostController'],
]);

it('formats model, policy and request names', function () {
    expect(Str::model('blog post'))->toBe('BlogPost')
        ->and(Str::policy('post'))->toBe('PostPolicy')
        ->and(Str::policy('PostPolicy.php'))->toBe('PostPolicy')
        ->and(Str::request('storePost'))->toBe('StorePostRequest')
        ->and(Str::request('StorePostRequest.php'))->toBe('StorePostRequest');
});

it('still exposes the Laravel string helpers', function () {
    expect(Str::studly('blog_post'))->toBe('BlogPost')
        ->and(Str::kebab('BlogPost'))->toBe('blog-post');
});
