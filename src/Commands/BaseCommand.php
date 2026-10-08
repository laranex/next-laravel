<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Laranex\NextLaravel\Decorator;
use Throwable;

abstract class BaseCommand extends Command
{
    /**
     * Run the generator and print the result; returns the exit code.
     *
     * @param  callable(): string  $generate
     */
    protected function generate(callable $generate): int
    {
        try {
            $this->printFileGeneratedOutput($generate());
        } catch (Throwable $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Print pretty output once a file has been generated.
     */
    public function printFileGeneratedOutput(string $output): void
    {
        $this->info(Decorator::getFileGeneratedOutput($output));
        $this->comment(Inspiring::quote());
    }

    /**
     * Print pretty output once file generation has failed.
     */
    public function printFileGenerationErrorOutput(string $output): void
    {
        $this->error(Decorator::getFileGenerationErrorOutput($output));
    }

    /**
     * Warn that generated route files are not loaded while routes are disabled.
     */
    public function printDisableRoutesWarning(): void
    {
        $this->error(Decorator::getDisableRoutesWarning());
    }

    protected function stringArgument(string $key): string
    {
        $value = $this->argument($key);

        return is_string($value) ? $value : '';
    }
}
