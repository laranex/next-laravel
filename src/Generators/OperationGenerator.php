<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use Laranex\NextLaravel\Str;

class OperationGenerator extends Generator
{
    /**
     * Generate a operation inside a module.
     *
     * @throws Exception
     */
    public function generate(string $operation, string $module, bool $force = false): string
    {
        $operation = Str::operation($operation);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Operations");
        $filePath = "$directoryPath/$operation.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'namespace' => "App\\Modules\\{$module}\\Operations",
            'operation' => $operation,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        return $this->stub('operation.php.stub');
    }
}
