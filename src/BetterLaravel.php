<?php

namespace Laranex\NextLaravel;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class NextLaravel
{
    public static function getAllFilesOfADirectory(string $directory, string $extension = ''): array
    {
        $files = [];

        if (! is_dir($directory)) {
            return $files;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && (! $extension || $fileInfo->getExtension() === $extension)) {
                $files[] = $fileInfo->getPathname();
            }
        }

        return $files;
    }
}
