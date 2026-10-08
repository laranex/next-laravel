<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use Laranex\NextLaravel\Str;

class RouteGenerator extends Generator
{
    /**
     * Generate a route file under routes/web or routes/api.
     *
     * @throws Exception
     */
    public function generate(string $route, string $versionOrDirectory = '', string $routeFileType = 'web', bool $force = false): string
    {
        $this->ensureNameIsNotNested($route, 'route');

        $route = Str::route($route);
        $versionOrDirectory = trim(Str::directory($versionOrDirectory), '/');

        $directoryPath = base_path(rtrim("routes/$routeFileType/$versionOrDirectory", '/'));
        $filePath = "$directoryPath/$route.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'prefix' => ltrim("$versionOrDirectory/$route", '/'),
            'route' => $route,
            // Kept with its leading slash for stubs published before {{prefix}} existed.
            'versionOrDirectory' => $versionOrDirectory !== '' ? "/$versionOrDirectory" : '',
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        return $this->stub('route.php.stub');
    }
}
