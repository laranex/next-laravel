# Changelog

All notable changes to `next-laravel` will be documented in this file.

## v4.0.0 - Unreleased

Versions 2.x and 3.x were never released; this release follows v1.1.0 directly so that every Laranex package shares the v4 line.

### Changed
- Requires PHP 8.1+ and supports Laravel 10 through 13.
- Rebuilt on the official Laravel package skeleton (Pest, PHPStan, Pint, Testbench workbench, GitHub Actions matrix).
- The package requires `laravel/framework` (`^10.0||^11.0||^12.0||^13.0`) instead of individual `illuminate/*` components, because it uses classes that ship only with the framework (`Illuminate\Foundation\Bus\DispatchesJobs`, `Http\FormRequest`, `Validation\ValidatesRequests`, `Inspiring`).
- `spatie/laravel-package-tools` was dropped; `NextLaravelServiceProvider` is a plain `Illuminate\Support\ServiceProvider`. Route files are now registered during `boot()` instead of `register()`.
- The generator commands (`next:route`, `next:controller`, `next:request`, `next:feature`, `next:operation`, `next:job`) exit with code `1` when generation fails (for example when the file already exists and `--force` was not given); they previously exited with `0`.
- `Str::studly()` is no longer overridden; the unused `$normalize` argument was removed. The Laravel method is inherited unchanged.
- `Bus\Dispatcher`, `Bus\ServesFeature` and `Bus\UnitDispatcher` now declare native `string|object` unit and `array` argument types; `Str` helpers, `NextLaravel::getAllFilesOfADirectory()` (now sorted) and the generators are fully typed. `RouteGenerator::getStubContents()` is public like the other generators.
- The whole source declares `strict_types=1`.
- The second argument of `next:operation` is now called `module` (it was `domain`, although the operation was always generated inside a module). Positional usage is unchanged.
- The generator commands resolve their generators from the container, and `next:route` treats a missing `next-laravel.enable_routes` key as enabled (the service provider already did), so it no longer warns that routes are disabled when the key is absent.
- The feature stub's `handle()` declares a `mixed` return type, like better-laravel.

### Fixed
- `UnitDispatcher::runInQueue()` no longer reads `composer.json` to build the "operation cannot be queued" error, which produced a broken message.
- The `job.queueable.php.stub` declared `__construct(): void`, which is invalid PHP; the stubs also no longer import unused classes.
- The disabled-routes warning named the wrong config key (`next-myanmar.enable_routes`).
- Generator names containing `/` or `\` (for example `next:feature Blog/createPost Blog`) produced a class named `Blog/CreatePostFeature`; every `next:*` command now rejects them with a clear error and exit code `1`.
- Generated route files no longer start their prefix with a slash (`Route::prefix('v1/posts')` instead of `'/v1/posts'`). The route stub uses a new `{{prefix}}` placeholder; `{{route}}` and `{{versionOrDirectory}}` are still filled so previously published stubs keep working.
- The feature stub's `handle()` declared a return type without returning anything, so a freshly generated feature threw a `TypeError` when served; it now returns `null` until you fill it in.

### Upgrading
- Require PHP 8.1+ and Laravel 10+ then `composer require laranex/next-laravel:^4.0`.
- If you published the config, re-publish it or keep it: the keys `enable_routes`, `web_routes_prefix` and `api_routes_prefix` are unchanged. Publish tags `next-laravel-config`, `next-laravel-views` and `next-laravel-stubs` are unchanged; a new `next-laravel` tag publishes all three at once.
- If you published the stubs, compare them with the new ones in `resources/stubs`; the generated class bodies kept the same shape.
- If a script relied on a generator command exiting with `0` on failure, check for the non-zero exit code instead.
- If you passed a second argument to `Laranex\NextLaravel\Str::studly()`, drop it.
- If you call `next:operation` with named arguments (`Artisan::call('next:operation', ['operation' => ..., 'domain' => ...])`), rename `domain` to `module`.
- If you passed names with `/` or `\` to the generators, pass a flat name instead; nested names are not supported.

## v1.1.0 - 2024-05-30

- Support Laravel 10 and 11.

## v1.0.0 - 2023-12-05

- Initial release.
