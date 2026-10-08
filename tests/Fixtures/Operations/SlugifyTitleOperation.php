<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Tests\Fixtures\Operations;

use Illuminate\Support\Str;
use Laranex\NextLaravel\Cores\Operation;

class SlugifyTitleOperation extends Operation
{
    public function __construct(public string $title) {}

    public function handle(): string
    {
        return Str::slug($this->title);
    }
}
