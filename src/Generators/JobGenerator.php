<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use Laranex\NextLaravel\Str;

class JobGenerator extends Generator
{
    /**
     * Generate a job inside a module.
     *
     * @throws Exception
     */
    public function generate(string $job, string $module, bool $queueable = false, bool $force = false): string
    {
        $this->ensureNameIsNotNested($job, 'job');
        $this->ensureNameIsNotNested($module, 'module');

        $job = Str::job($job);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Jobs");
        $filePath = "$directoryPath/$job.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents($queueable), [
            'namespace' => "App\\Modules\\{$module}\\Jobs",
            'job' => $job,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(bool $queueable = false): string
    {
        return $this->stub($queueable ? 'job.queueable.php.stub' : 'job.php.stub');
    }
}
