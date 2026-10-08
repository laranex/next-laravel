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
        $route = Str::route($route);
        $versionOrDirectory = Str::directory($versionOrDirectory);

        $versionOrDirectory = $versionOrDirectory !== '' ? "/$versionOrDirectory" : '';

        $directoryPath = base_path("routes/$routeFileType$versionOrDirectory");
        $filePath = "$directoryPath/$route.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'route' => $route,
            'versionOrDirectory' => $versionOrDirectory,
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
