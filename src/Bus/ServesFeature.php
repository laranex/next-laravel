<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Bus;

use Illuminate\Foundation\Bus\DispatchesJobs;

trait ServesFeature
{
    use Dispatcher, DispatchesJobs;

    /**
     * Serve the given feature with the given arguments.
     *
     * @param  class-string|object  $feature
     * @param  array<int|string, mixed>  $arguments
     */
    public function serve(string|object $feature, array $arguments = []): mixed
    {
        return $this->dispatchSync($this->getDispatchableUnit($feature, $arguments));
    }
}
