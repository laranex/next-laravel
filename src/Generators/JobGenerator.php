<?php

namespace Laranex\NextLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\NextLaravel\Str;

class JobGenerator extends Generator
{
    /**
     * Generate a job.
     *
     *
     * @throws Exception
     */
    public function generate(string $job, string $domain, bool $queueable = false, bool $force = false): string
    {
        $job = Str::job($job);
        $domain = Str::domain($domain);

        $directoryPath = app_path("Domains/{$domain}/Jobs");
        $filename = "{$job}.php";
        $filePath = "{$directoryPath}/{$filename}";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents($queueable);

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Domains\\{$domain}\\Jobs",
            'job' => $job,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(bool $queueable): string
    {
        $filePart = $queueable ? '.queueable' : '';

        $stubFile = resource_path("stubs/vendor/next-laravel/job$filePart.php.stub");
        if (! File::exists($stubFile)) {
            $stubFile = __DIR__."/../../resources/stubs/job$filePart.php.stub";
        }

        return File::get($stubFile);
    }
}
