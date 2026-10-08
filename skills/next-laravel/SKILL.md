---
name: next-laravel
description: >
  Structure a Laravel application into modules (controllers, requests, features, operations, jobs) with laranex/next-laravel, generate units with the next:* Artisan commands and let route files under routes/web and routes/api be discovered.
license: MIT
metadata:
  author: Nay Thu Khant
---

# Next Laravel

## When to use

Use this skill when a Laravel application uses `laranex/next-laravel` to organize code into modules under `app/Modules`: each `{Name}Module` owns its controllers, form requests, features, operations and jobs, built on the base classes in `Laranex\NextLaravel\Cores`. Generate every unit with the `next:*` commands and wire controller → feature → operations/jobs through the package helpers.

## Install

```bash
composer require laranex/next-laravel
```

Requires PHP 8.1+ and Laravel 10 to 13. The service provider and the `NextLaravel` facade are auto-discovered.

## Configure

- Every PHP file under `routes/web` is loaded with the `web` middleware group, every file under `routes/api` with the `api` group. Routes are not registered again while the route cache is active.
- `NEXT_LARAVEL_ENABLE_ROUTES` (default `true`): set to `false` to turn discovery off.
- `NEXT_LARAVEL_WEB_ROUTES_PREFIX` (default empty) and `NEXT_LARAVEL_API_ROUTES_PREFIX` (default `api`): URI prefixes for the two folders.
- Publish only what you change: `php artisan vendor:publish --tag="next-laravel-config"`, `--tag="next-laravel-stubs"` (edit the generator stubs in `resources/stubs/vendor/next-laravel`) or `--tag="next-laravel-views"`.

## Use

### Generate units

Every command accepts `--force` to overwrite an existing file and exits `1` (printing `... already exists!`) when the file exists. Module names are normalized: `blog`, `Blog` and `BlogModule` all mean `BlogModule`, and missing suffixes are added. Names must not contain `/` or `\`.

- `php artisan next:controller post blog` → `app/Modules/BlogModule/Http/Controllers/PostController.php`
- `php artisan next:request storePost blog` → `app/Modules/BlogModule/Http/Requests/StorePostRequest.php`
- `php artisan next:feature createPost blog` → `app/Modules/BlogModule/Features/CreatePostFeature.php`
- `php artisan next:operation slugifyTitle blog` → `app/Modules/BlogModule/Operations/SlugifyTitleOperation.php`
- `php artisan next:job sendWelcomeEmail blog [--queue]` → `app/Modules/BlogModule/Jobs/SendWelcomeEmailJob.php` (`--queue` extends `QueueableJob`, otherwise a synchronous `Job`)
- `php artisan next:route post` → `routes/web/posts.php`; `php artisan next:route post v1 --api` → `routes/api/v1/posts.php`

### Compose units

- Controllers extend `Laranex\NextLaravel\Cores\Controller` and call `$this->serve(CreatePostFeature::class, ['title' => $value])`. The array is passed to the feature's constructor as named arguments; `handle()` receives container-resolved dependencies such as `Illuminate\Http\Request`.
- Features and operations extend `Cores\Feature` / `Cores\Operation` and call `$this->run(Unit::class, [...])` to run an operation or job synchronously (an instance works too) and `$this->runInQueue(Job::class, [...], 'queue-name')` for a `QueueableJob`.
- Form requests extend `Cores\Request` (a `FormRequest`).

```php
// Controller
return $this->serve(CreatePostFeature::class, ['title' => $request->string('title')->toString()]);

// CreatePostFeature::handle()
$slug = $this->run(SlugifyTitleOperation::class, ['title' => $this->title]);
$this->runInQueue(SendWelcomeEmailJob::class, ['email' => $email], 'emails');
```

## Test your app

- Call the route and assert the response; `serve()` and `run()` execute synchronously.
- Use `Queue::fake()` and `Queue::assertPushedOn('emails', SendWelcomeEmailJob::class)` for `runInQueue()`. With `Bus::fake()` the synchronous `run()` and `serve()` calls are faked too and return `null`.
- Unit-test a job or operation by constructing it and calling `handle()` directly.

## Avoid

- Queueing an operation or a plain `Job`: `runInQueue()` throws `Error`; move the work into a `QueueableJob`.
- Putting module routes in `routes/web.php`; one file per module under `routes/web/` keeps discovery working.
- Hand-writing unit classes with other suffixes; the generators expect `*Feature`, `*Operation`, `*Job`, `*Controller` and `*Request`.
