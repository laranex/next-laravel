<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use Laranex\NextLaravel\Str;

class RequestGenerator extends Generator
{
    /**
     * Generate a request inside a module.
     *
     * @throws Exception
     */
    public function generate(string $request, string $module, bool $force = false): string
    {
        $this->ensureNameIsNotNested($request, 'request');
        $this->ensureNameIsNotNested($module, 'module');

        $request = Str::request($request);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Http/Requests");
        $filePath = "$directoryPath/$request.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'namespace' => "App\\Modules\\{$module}\\Http\\Requests",
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
        return $this->stub('request.php.stub');
    }
}
