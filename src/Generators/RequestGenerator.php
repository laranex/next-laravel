<?php

namespace Laranex\NextLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\NextLaravel\Str;

class RequestGenerator extends Generator
{
    /**
     * Generate a job.
     *
     *
     * @throws Exception
     */
    public function generate(string $request, string $domain, bool $force = false): string
    {
        $request = Str::request($request);
        $domain = Str::domain($domain);

        $directoryPath = app_path("Domains/{$domain}/Requests");
        $filename = "{$request}.php";
        $filePath = "{$directoryPath}/{$filename}";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents();

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Domains\\{$domain}\\Requests",
            'request' => $request,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        $stubFile = resource_path('stubs/vendor/next-laravel/request.php.stub');
        if (! File::exists($stubFile)) {
            $stubFile = __DIR__.'/../../resources/stubs/request.php.stub';
        }

        return File::get($stubFile);
    }
}
