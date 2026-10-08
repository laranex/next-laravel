<?php

declare(strict_types=1);

namespace Laranex\NextLaravel;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class NextLaravel
{
    /**
     * Recursively list the files of a directory, optionally filtered by extension.
     *
     * @return list<string>
     */
    public static function getAllFilesOfADirectory(string $directory, string $extension = ''): array
    {
        $files = [];

        if (! is_dir($directory)) {
            return $files;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $fileInfo) {
            if (! $fileInfo instanceof SplFileInfo || ! $fileInfo->isFile()) {
                continue;
            }

            if ($extension === '' || $fileInfo->getExtension() === $extension) {
                $files[] = $fileInfo->getPathname();
            }
        }

        sort($files);

        return $files;
    }
}
