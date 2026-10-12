<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Bus;

use InvalidArgumentException;
use Laranex\NextLaravel\Cores\Feature;
use Laranex\NextLaravel\Cores\Job;
use Laranex\NextLaravel\Cores\Operation;

trait Dispatcher
{
    /**
     * Get the dispatchable unit, instantiating it from a class name when needed.
     *
     * Class names must extend the Feature, Operation or Job base class; anything else is rejected
     * instead of being instantiated.
     *
     * @param  class-string|object  $unit
     * @param  array<int|string, mixed>  $arguments
     *
     * @throws InvalidArgumentException when a class name is not a Feature, Operation or Job
     */
    public function getDispatchableUnit(string|object $unit, array $arguments): object
    {
        if (is_object($unit)) {
            return $unit;
        }

        if (! $this->isDispatchableUnitClass($unit)) {
            throw new InvalidArgumentException(sprintf('[%s] cannot be dispatched: pass a class that extends [%s], [%s] or [%s].', $unit, Feature::class, Operation::class, Job::class));
        }

        return new $unit(...$arguments);
    }

    /**
     * Determine whether the class name extends one of the unit base classes.
     */
    private function isDispatchableUnitClass(string $class): bool
    {
        return is_subclass_of($class, Feature::class)
            || is_subclass_of($class, Operation::class)
            || is_subclass_of($class, Job::class);
    }
}
