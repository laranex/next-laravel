<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\NextLaravel\Decorator;

abstract class Generator
{
    /**
     * Replace the {{placeholder}} tokens of a stub.
     *
     * @param  array<string, string>  $replacements
     */
    public function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $placeholder => $replacement) {
            if ($placeholder === 'namespace') {
                $replacement = str_replace('/', '\\', $replacement);
            }

            $content = str_replace('{{'.$placeholder.'}}', $replacement, $content);
        }

        return $content;
    }

    /**
     * Throw when the given file exists and the force option is off.
     *
     * @throws Exception
     */
    public function throwIfFileExists(string $filePath, bool $force = false): void
    {
        if (File::exists($filePath) && ! $force) {
            $path = Decorator::getRelativePath($filePath);

            throw new Exception("$path already exists!");
        }
    }

    /**
     * Write the replaced stub contents to a file, creating the directory when needed.
     */
    public function generateFile(string $directoryPath, string $filePath, string $stubContents): void
    {
        if (! File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        File::put($filePath, $stubContents);
    }

    /**
     * Read a stub, preferring one published to resources/stubs/vendor/next-laravel.
     */
    protected function stub(string $name): string
    {
        $stubFile = resource_path("stubs/vendor/next-laravel/$name");

        if (! File::exists($stubFile)) {
            $stubFile = __DIR__."/../../resources/stubs/$name";
        }

        return File::get($stubFile);
    }
}
