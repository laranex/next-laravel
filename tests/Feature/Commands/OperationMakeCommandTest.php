<?php

declare(strict_types=1);

beforeEach(fn () => $this->cleanup(app_path('Modules/BillingModule')));

it('generates an operation inside the domain module', function () {
    $this->artisan('next:operation', ['operation' => 'chargeCard', 'domain' => 'billing'])
        ->expectsOutputToContain('Modules/BillingModule/Operations/ChargeCardOperation.php has been successfully generated!')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/BillingModule/Operations/ChargeCardOperation.php')))
        ->toContain('namespace App\Modules\BillingModule\Operations;')
        ->toContain('class ChargeCardOperation extends Operation')
        ->toContain('public function handle(): void');
});

it('fails when the operation already exists', function () {
    $this->writeFile(app_path('Modules/BillingModule/Operations/ChargeCardOperation.php'), 'original');

    $this->artisan('next:operation', ['operation' => 'chargeCard', 'domain' => 'billing'])
        ->expectsOutputToContain('ChargeCardOperation.php already exists!')
        ->assertExitCode(1);

    $this->artisan('next:operation', ['operation' => 'chargeCard', 'domain' => 'billing', '--force' => true])->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/BillingModule/Operations/ChargeCardOperation.php')))->toContain('class ChargeCardOperation');
});
