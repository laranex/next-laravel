<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Bus;

trait Dispatcher
{
    /**
     * Get the dispatchable unit, instantiating it from a class name when needed.
     *
     * @param  class-string|object  $unit
     * @param  array<int|string, mixed>  $arguments
     */
    public function getDispatchableUnit(string|object $unit, array $arguments): object
    {
        return is_string($unit) ? new $unit(...$arguments) : $unit;
    }
}
