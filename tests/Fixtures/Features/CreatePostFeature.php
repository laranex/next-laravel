<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Tests\Fixtures\Features;

use Illuminate\Http\Request;
use Laranex\NextLaravel\Cores\Feature;
use Laranex\NextLaravel\Tests\Fixtures\Operations\SlugifyTitleOperation;

class CreatePostFeature extends Feature
{
    public function __construct(public string $title) {}

    /**
     * @return array{title: string, slug: string, path: string}
     */
    public function handle(Request $request): array
    {
        $slug = $this->run(SlugifyTitleOperation::class, ['title' => $this->title]);

        return [
            'title' => $this->title,
            'slug' => is_string($slug) ? $slug : '',
            'path' => $request->path(),
        ];
    }
}
