<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Tests\Fixtures\Http\Controllers;

use Laranex\NextLaravel\Cores\Controller;
use Laranex\NextLaravel\Tests\Fixtures\Features\CreatePostFeature;

class PostController extends Controller
{
    public function store(string $title): mixed
    {
        return $this->serve(CreatePostFeature::class, ['title' => $title]);
    }
}
