---
name: next-laravel-development
description: >
  Structure a Laravel application into modules (controllers, requests, features, operations, jobs) with laranex/next-laravel, generate units with the next:* Artisan commands and let route files under routes/web and routes/api be discovered.
license: MIT
metadata:
  author: Nay Thu Khant
---

# Next Laravel

Use this skill when a Laravel application uses laranex/next-laravel to organize code into modules under `app/Modules`.

## Primary Goal

- put every unit in its module with the generators and wire controller -> feature -> operations/jobs through the package base classes

## Workflow

### 1. Generate units

- `php artisan next:controller post blog` -> `app/Modules/BlogModule/Http/Controllers/PostController.php`
- `php artisan next:request storePost blog` -> `Http/Requests/StorePostRequest.php`
- `php artisan next:feature createPost blog` -> `Features/CreatePostFeature.php`
- `php artisan next:operation slugifyTitle blog` -> `Operations/SlugifyTitleOperation.php`
- `php artisan next:job sendEmail blog --queue` -> `Jobs/SendEmailJob.php` extending `QueueableJob`; omit `--queue` for a synchronous `Job`
- names are normalized: `blog`, `Blog` and `BlogModule` all mean `BlogModule`; suffixes (`Feature`, `Controller`, ...) are added when missing
- the commands exit with `1` and print `... already exists!` when the file exists; pass `--force` to overwrite
- names must not contain `/` or `\`; nested names such as `Blog/CreatePost` are rejected with exit code `1`

### 2. Compose units

- controllers extend `Laranex\NextLaravel\Cores\Controller` and call `$this->serve(SomeFeature::class, ['arg' => $value])`; the feature's constructor receives the array as named arguments and `handle()` receives container-resolved dependencies such as `Illuminate\Http\Request`
- features and operations extend `Cores\Feature` / `Cores\Operation` and call `$this->run(Unit::class, [...])` for synchronous operations or jobs, `$this->runInQueue(Job::class, [...], 'queue-name')` for a `QueueableJob`
- `runInQueue()` throws `Error` for an `Operation` or a plain `Job`; only `QueueableJob` subclasses can be queued
- form requests extend `Cores\Request` (a `FormRequest`)

### 3. Route files

- `php artisan next:route post` creates `routes/web/posts.php`; `php artisan next:route post v1 --api` creates `routes/api/v1/posts.php`
- every PHP file under `routes/web` is loaded with the `web` middleware group and `NEXT_LARAVEL_WEB_ROUTES_PREFIX` (default none); files under `routes/api` get the `api` group and `NEXT_LARAVEL_API_ROUTES_PREFIX` (default `api`)
- `NEXT_LARAVEL_ENABLE_ROUTES=false` turns discovery off; routes are not re-registered when the route cache is active

### 4. Customize

- `php artisan vendor:publish --tag="next-laravel-config"` for `config/next-laravel.php`
- `php artisan vendor:publish --tag="next-laravel-stubs"` to edit the generator stubs in `resources/stubs/vendor/next-laravel`; placeholders are `{{namespace}}` plus `{{controller}}`, `{{request}}`, `{{feature}}`, `{{operation}}`, `{{job}}`, `{{prefix}}` (route prefix such as `v1/posts`), plus `{{route}}` and `{{versionOrDirectory}}` kept for older route stubs

## Rules, References, and Templates

- no additional resource files for this skill

## Examples

- `return $this->serve(CreatePostFeature::class, ['title' => $request->string('title')]);`
- `$slug = $this->run(SlugifyTitleOperation::class, ['title' => $this->title]);`
- `$this->runInQueue(SendWelcomeEmailJob::class, ['email' => $email], 'emails');`

## Anti-patterns

- do not put module routes in `routes/web.php`; one file per module under `routes/web/` keeps discovery working
- do not queue operations; move the work into a `QueueableJob`
- do not hand-write unit classes with other suffixes; the generators and `Str` helpers expect `*Feature`, `*Operation`, `*Job`, `*Controller`, `*Request`
