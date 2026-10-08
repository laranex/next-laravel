<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Tests\Fixtures\Jobs;

use Laranex\NextLaravel\Cores\QueueableJob;

class SendWelcomeEmailJob extends QueueableJob
{
    public function __construct(public string $email) {}

    public function handle(): void
    {
        //
    }
}
