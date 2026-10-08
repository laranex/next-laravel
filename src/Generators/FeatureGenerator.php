<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Generators;

use Exception;
use Laranex\NextLaravel\Str;

class FeatureGenerator extends Generator
{
    /**
     * Generate a feature inside a module.
     *
     * @throws Exception
     */
    public function generate(string $feature, string $module, bool $force = false): string
    {
        $feature = Str::feature($feature);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Features");
        $filePath = "$directoryPath/$feature.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'namespace' => "App\\Modules\\{$module}\\Features",
            'feature' => $feature,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        return $this->stub('feature.php.stub');
    }
}
