<?php

namespace Laranex\NextLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Laranex\NextLaravel\Decorator;

class BaseCommand extends Command
{
    /**
     * Print pretty output once file has been generated.
     */
    public function printFileGeneratedOutput(string $output): void
    {
        $this->info(Decorator::getFileGeneratedOutput($output));
        $this->comment(Inspiring::quote());
    }

    /**
     * Print pretty output once file has occurred error.
     */
    public function printFileGenerationErrorOutput(string $output): void
    {
        $this->error(Decorator::getFileGenerationErrorOutput($output));
    }

    /**
     * Print pretty output once file has occurred error.
     */
    public function printDisableRoutesWarning(): void
    {
        $this->error(Decorator::getDisableRoutesWarning());
    }
}
