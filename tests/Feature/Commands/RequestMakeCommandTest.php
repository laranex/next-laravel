<?php

declare(strict_types=1);

beforeEach(fn () => $this->cleanup(app_path('Modules/ShopModule')));

it('generates a form request inside the module', function () {
    $this->artisan('next:request', ['request' => 'storeOrder', 'module' => 'shop'])
        ->expectsOutputToContain('Modules/ShopModule/Http/Requests/StoreOrderRequest.php has been successfully generated!')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/ShopModule/Http/Requests/StoreOrderRequest.php')))
        ->toContain('namespace App\Modules\ShopModule\Http\Requests;')
        ->toContain('class StoreOrderRequest extends Request')
        ->toContain('public function rules(): array');
});

it('fails when the request already exists', function () {
    $this->writeFile(app_path('Modules/ShopModule/Http/Requests/StoreOrderRequest.php'), 'original');

    $this->artisan('next:request', ['request' => 'storeOrder', 'module' => 'shop'])
        ->expectsOutputToContain('StoreOrderRequest.php already exists!')
        ->assertExitCode(1);

    $this->artisan('next:request', ['request' => 'storeOrder', 'module' => 'shop', '-F' => true])->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/ShopModule/Http/Requests/StoreOrderRequest.php')))->toContain('class StoreOrderRequest');
});
