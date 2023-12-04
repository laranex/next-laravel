<?php

namespace Laranex\NextLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laranex\NextLaravel\NextLaravel
 */
class NextLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Laranex\NextLaravel\NextLaravel::class;
    }
}
