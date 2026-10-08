<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Tests\Fixtures\Jobs;

use Laranex\NextLaravel\Cores\Job;

class RecordPostJob extends Job
{
    public function __construct(public string $slug) {}

    public function handle(): string
    {
        return "recorded:{$this->slug}";
    }
}
