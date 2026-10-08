<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Bus;

use Error;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Laranex\NextLaravel\Cores\Operation;
use Laranex\NextLaravel\Cores\QueueableJob;

trait UnitDispatcher
{
    use Dispatcher, DispatchesJobs;

    /**
     * Run the given unit (operation or job) synchronously with the given arguments.
     *
     * @param  class-string|object  $unit
     * @param  array<int|string, mixed>  $arguments
     */
    public function run(string|object $unit, array $arguments = []): mixed
    {
        return $this->dispatchSync($this->getDispatchableUnit($unit, $arguments));
    }

    /**
     * Dispatch the given unit with the given arguments onto the given queue.
     *
     * @param  class-string|object  $unit
     * @param  array<int|string, mixed>  $arguments
     *
     * @throws Error when the unit cannot be queued
     */
    public function runInQueue(string|object $unit, array $arguments = [], string $queue = 'default'): mixed
    {
        $dispatchableUnit = $this->getDispatchableUnit($unit, $arguments);

        if (! method_exists($dispatchableUnit, 'onQueue')) {
            if ($dispatchableUnit instanceof Operation) {
                throw new Error('['.$dispatchableUnit::class.' is an Operation and is not allowed to be queued yet, laranex/next-laravel will be providing it soon]');
            }

            throw new Error('['.$dispatchableUnit::class.' does not support queues. Please extend ['.QueueableJob::class.']]');
        }

        $dispatchableUnit->onQueue($queue);

        return $this->dispatch($dispatchableUnit);
    }
}
