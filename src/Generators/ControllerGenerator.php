<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use Laranex\NextLaravel\Str;

class ControllerGenerator extends Generator
{
    /**
     * Generate a controller inside a module.
     *
     * @throws Exception
     */
    public function generate(string $controller, string $module, bool $force = false): string
    {
        $controller = Str::controller($controller);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Http/Controllers");
        $filePath = "$directoryPath/$controller.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'namespace' => "App\\Modules\\{$module}\\Http\\Controllers",
            'controller' => $controller,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        return $this->stub('controller.php.stub');
    }
}
