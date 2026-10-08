<?php

declare(strict_types=1);

beforeEach(fn () => $this->cleanup(app_path('Modules/CatalogModule')));

it('generates a feature inside the module', function () {
    $this->artisan('next:feature', ['feature' => 'list products', 'module' => 'catalog'])
        ->expectsOutputToContain('Modules/CatalogModule/Features/ListProductsFeature.php has been successfully generated!')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/CatalogModule/Features/ListProductsFeature.php')))
        ->toContain('namespace App\Modules\CatalogModule\Features;')
        ->toContain('class ListProductsFeature extends Feature')
        ->toContain('public function handle(Request $request): mixed');
});

it('fails when the feature already exists', function () {
    $this->writeFile(app_path('Modules/CatalogModule/Features/ListProductsFeature.php'), 'original');

    $this->artisan('next:feature', ['feature' => 'ListProductsFeature', 'module' => 'CatalogModule'])
        ->expectsOutputToContain('ListProductsFeature.php already exists!')
        ->assertExitCode(1);

    $this->artisan('next:feature', ['feature' => 'ListProductsFeature', 'module' => 'CatalogModule', '--force' => true])->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/CatalogModule/Features/ListProductsFeature.php')))->toContain('class ListProductsFeature');
});
