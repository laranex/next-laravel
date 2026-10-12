<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use InvalidArgumentException;
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
        $this->ensureRouteFileTypeIsValid($routeFileType);

        $route = $this->validRouteName(Str::route($route), $route);
        $versionOrDirectory = $this->validDirectory(trim(Str::directory($versionOrDirectory), '/'), $versionOrDirectory);

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
     * Return the generated route name, rejecting anything but lowercase letters, numbers, "-" and "_".
     *
     * @throws InvalidArgumentException
     */
    public function validRouteName(string $route, string $name): string
    {
        if (preg_match('/^[a-z0-9_-]+$/', $route) !== 1) {
            throw new InvalidArgumentException(sprintf('The route name [%s] is invalid: [%s] may only contain lowercase letters, numbers, "-" and "_".', $name, $route));
        }

        return $route;
    }

    /**
     * Return the route subdirectory, rejecting segments other than lowercase letters, numbers, "-" and "_" (so no "..").
     *
     * @throws InvalidArgumentException
     */
    public function validDirectory(string $directory, string $name): string
    {
        if ($directory === '') {
            return $directory;
        }

        foreach (explode('/', $directory) as $segment) {
            if (preg_match('/^[a-z0-9_-]+$/', $segment) !== 1) {
                throw new InvalidArgumentException(sprintf('The versionOrDirectory [%s] is invalid: use "/"-separated segments of lowercase letters, numbers, "-" and "_" only.', $name));
            }
        }

        return $directory;
    }

    /**
     * Only routes/web and routes/api are loaded, so only those are generated.
     *
     * @throws InvalidArgumentException
     */
    public function ensureRouteFileTypeIsValid(string $routeFileType): void
    {
        if (! in_array($routeFileType, ['web', 'api'], true)) {
            throw new InvalidArgumentException(sprintf('The route file type [%s] is invalid: use "web" or "api".', $routeFileType));
        }
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        return $this->stub('route.php.stub');
    }
}
