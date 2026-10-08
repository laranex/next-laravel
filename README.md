# Next Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laranex/next-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/next-laravel)
[![Tests](https://img.shields.io/github/actions/workflow/status/laranex/next-laravel/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/laranex/next-laravel/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laranex/next-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/next-laravel)
[![License](https://img.shields.io/packagist/l/laranex/next-laravel.svg?style=flat-square)](LICENSE.md)

Next Laravel gives a Laravel application a modular architecture: each module under `app/Modules` owns its controllers, form requests, features, operations and jobs, route files under `routes/web` and `routes/api` are discovered automatically, and an Artisan generator exists for every unit. It is for teams who want a predictable structure for growing Laravel applications without leaving the framework's conventions.

## Documentation

Full documentation lives at **[laranex.vercel.app/next-laravel](https://laranex.vercel.app/next-laravel)**.

## Requirements

- PHP 8.1 or higher
- Laravel 10, 11, 12 or 13

## Installation

```bash
composer require laranex/next-laravel
```

Optionally publish the config, the stubs the generators use, or the welcome view:

```bash
php artisan vendor:publish --tag="next-laravel-config"
php artisan vendor:publish --tag="next-laravel-stubs"
php artisan vendor:publish --tag="next-laravel-views"
```

## Usage

Generate a module's units and a route file:

```bash
php artisan next:route post                 # routes/web/posts.php
php artisan next:route post v1 --api        # routes/api/v1/posts.php
php artisan next:controller post blog       # app/Modules/BlogModule/Http/Controllers/PostController.php
php artisan next:request storePost blog     # app/Modules/BlogModule/Http/Requests/StorePostRequest.php
php artisan next:feature createPost blog    # app/Modules/BlogModule/Features/CreatePostFeature.php
php artisan next:operation slugifyTitle blog
php artisan next:job sendWelcomeEmail blog --queue
```

A controller serves a feature, and a feature runs operations and jobs:

```php
use App\Modules\BlogModule\Features\CreatePostFeature;
use App\Modules\BlogModule\Http\Requests\StorePostRequest;
use Laranex\NextLaravel\Cores\Controller;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        return $this->serve(CreatePostFeature::class, ['title' => $request->string('title')]);
    }
}

class CreatePostFeature extends Feature
{
    public function __construct(public string $title) {}

    public function handle(Request $request)
    {
        $slug = $this->run(SlugifyTitleOperation::class, ['title' => $this->title]);

        $this->runInQueue(SendWelcomeEmailJob::class, ['email' => $request->user()->email], 'emails');

        return response()->json(['slug' => $slug]);
    }
}
```

Every PHP file under `routes/web` is registered with the `web` middleware group and every file under `routes/api` with the `api` group and the `api` prefix. Set `NEXT_LARAVEL_ENABLE_ROUTES`, `NEXT_LARAVEL_WEB_ROUTES_PREFIX` or `NEXT_LARAVEL_API_ROUTES_PREFIX` to change that. See the [documentation](https://laranex.vercel.app/next-laravel) for more.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Nay Thu Khant](https://github.com/NayThuKhant)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
