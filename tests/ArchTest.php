<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

if (InstalledVersions::satisfies(new VersionParser, 'pestphp/pest', '^3.0||^4.0')) {
    arch()->preset()->php();

    arch()->preset()->security();
}

arch('it will not use dd(), ddd(), env(), or exit()')
    ->expect(['dd', 'ddd', 'env', 'exit'])
    ->each->not->toBeUsed();

arch('the package source declares strict types')
    ->expect('Laranex\NextLaravel')
    ->toUseStrictTypes();
