<?php

namespace Laranex\NextLaravel\Bus;

use Error;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Laranex\NextLaravel\Cores\Operation;
use Laranex\NextLaravel\Cores\QueueableJob;

trait UnitDispatcher
{
    use Dispatcher, DispatchesJobs;

    /**
     * Dispatch the given unit with the given arguments.
     *
     * @param  string  $unit
     */
    public function run(mixed $unit, array $arguments = []): mixed
    {
        return $this->dispatchSync($this->getDispatchableUnit($unit, $arguments));
    }

    /**
     * Serve the given unit with arguments in given queue.
     *
     * @param  string  $unit
     *
     * @throws Error
     */
    public function runInQueue(mixed $unit, array $arguments = [], string $queue = 'default'): mixed
    {
        $dispatchableUnit = $this->getDispatchableUnit($unit, $arguments);

        try {
            $dispatchableUnit->onQueue($queue);
        } catch (Error $_) {

            /**
             * TODO remove the following condition once we provide QueueableOperation.
             * We put this here, instead of the very first line of this method since we dont want to effect the application performance
             * on normal queueable jobs by always checking a condition.
             */
            if ($dispatchableUnit instanceof Operation) {
                $packageName = json_decode(file_get_contents(dirname(__DIR__, 2).'/composer.json', true))?->name;
                throw new Error('['.$dispatchableUnit::class."is an Operation and is not allowed to be queue yet, $packageName will be providing it soon ]");
            }

            throw new Error('['.$dispatchableUnit::class.' does not support queues. Please extends to ['.QueueableJob::class.']');
        }

        return $this->dispatch($dispatchableUnit);
    }
}
