<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static list<string> getAllFilesOfADirectory(string $directory, string $extension = '')
 *
 * @see \Laranex\NextLaravel\NextLaravel
 */
class NextLaravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Laranex\NextLaravel\NextLaravel::class;
    }
}
