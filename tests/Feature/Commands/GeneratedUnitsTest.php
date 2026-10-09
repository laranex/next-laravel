<?php

declare(strict_types=1);

use App\Modules\GeneratedUnitsModule\Features\UntouchedFeature;
use App\Modules\GeneratedUnitsModule\Http\Controllers\UntouchedController;
use App\Modules\GeneratedUnitsModule\Http\Requests\UntouchedRequest;
use App\Modules\GeneratedUnitsModule\Jobs\UntouchedJob;
use App\Modules\GeneratedUnitsModule\Jobs\UntouchedQueuedJob;
use App\Modules\GeneratedUnitsModule\Operations\UntouchedOperation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Queue;
use Laranex\NextLaravel\Cores\Controller;
use Laranex\NextLaravel\Cores\Feature;
use Laranex\NextLaravel\Cores\Job;
use Laranex\NextLaravel\Cores\Operation;
use Laranex\NextLaravel\Cores\QueueableJob;

beforeEach(fn () => $this->cleanup(app_path('Modules/GeneratedUnitsModule')));

it('generates units that load and run without any edits', function () {
    $this->artisan('next:controller', ['controller' => 'Untouched', 'module' => 'GeneratedUnits'])->assertExitCode(0);
    $this->artisan('next:request', ['request' => 'Untouched', 'module' => 'GeneratedUnits'])->assertExitCode(0);
    $this->artisan('next:feature', ['feature' => 'Untouched', 'module' => 'GeneratedUnits'])->assertExitCode(0);
    $this->artisan('next:operation', ['operation' => 'Untouched', 'module' => 'GeneratedUnits'])->assertExitCode(0);
    $this->artisan('next:job', ['job' => 'Untouched', 'module' => 'GeneratedUnits'])->assertExitCode(0);
    $this->artisan('next:job', ['job' => 'UntouchedQueued', 'module' => 'GeneratedUnits', '--queue' => true])->assertExitCode(0);

    foreach ([
        'Http/Controllers/UntouchedController.php',
        'Http/Requests/UntouchedRequest.php',
        'Features/UntouchedFeature.php',
        'Operations/UntouchedOperation.php',
        'Jobs/UntouchedJob.php',
        'Jobs/UntouchedQueuedJob.php',
    ] as $file) {
        require_once app_path("Modules/GeneratedUnitsModule/$file");
    }

    $controller = new UntouchedController;
    $request = new UntouchedRequest;
    $feature = new UntouchedFeature;

    expect($controller)->toBeInstanceOf(Controller::class)
        ->and($request)->toBeInstanceOf(FormRequest::class)
        ->and($request->authorize())->toBeFalse()
        ->and($request->rules())->toBe([])
        ->and($feature)->toBeInstanceOf(Feature::class)
        ->and(new UntouchedOperation)->toBeInstanceOf(Operation::class)
        ->and(new UntouchedJob)->toBeInstanceOf(Job::class)
        ->and(new UntouchedQueuedJob)->toBeInstanceOf(QueueableJob::class)
        ->and($controller->serve(UntouchedFeature::class))->toBeNull()
        ->and($feature->run(UntouchedOperation::class))->toBeNull()
        ->and($feature->run(UntouchedJob::class))->toBeNull();

    Queue::fake();

    $feature->runInQueue(UntouchedQueuedJob::class, [], 'generated');

    Queue::assertPushedOn('generated', UntouchedQueuedJob::class);
});
