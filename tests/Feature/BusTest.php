<?php

declare(strict_types=1);

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Bus;
use Laranex\NextLaravel\Cores\Controller;
use Laranex\NextLaravel\Cores\Feature;
use Laranex\NextLaravel\Cores\Operation;
use Laranex\NextLaravel\Cores\QueueableJob;
use Laranex\NextLaravel\Cores\Request;
use Laranex\NextLaravel\Tests\Fixtures\Features\CreatePostFeature;
use Laranex\NextLaravel\Tests\Fixtures\Http\Controllers\PostController;
use Laranex\NextLaravel\Tests\Fixtures\Jobs\RecordPostJob;
use Laranex\NextLaravel\Tests\Fixtures\Jobs\SendWelcomeEmailJob;
use Laranex\NextLaravel\Tests\Fixtures\Operations\SlugifyTitleOperation;

it('serves a feature from a controller and injects the request into handle()', function () {
    $result = (new PostController)->store('Hello World');

    expect($result)->toBe(['title' => 'Hello World', 'slug' => 'hello-world', 'path' => '/']);
});

it('serves an already constructed feature', function () {
    expect((new PostController)->serve(new CreatePostFeature('Next Laravel')))
        ->toMatchArray(['title' => 'Next Laravel', 'slug' => 'next-laravel']);
});

it('runs operations and jobs synchronously from a feature or an operation', function () {
    $feature = new CreatePostFeature('x');
    $operation = new SlugifyTitleOperation('x');

    expect($feature->run(SlugifyTitleOperation::class, ['title' => 'Some Title']))->toBe('some-title')
        ->and($feature->run(new RecordPostJob('some-title')))->toBe('recorded:some-title')
        ->and($operation->run(RecordPostJob::class, ['other']))->toBe('recorded:other');
});

it('dispatches queueable jobs onto the given queue', function () {
    Bus::fake();

    (new CreatePostFeature('x'))->runInQueue(SendWelcomeEmailJob::class, ['email' => 'a@b.test'], 'emails');
    (new CreatePostFeature('x'))->runInQueue(new SendWelcomeEmailJob('c@d.test'));

    Bus::assertDispatched(SendWelcomeEmailJob::class, fn (SendWelcomeEmailJob $job): bool => $job->email === 'a@b.test' && $job->queue === 'emails');
    Bus::assertDispatched(SendWelcomeEmailJob::class, fn (SendWelcomeEmailJob $job): bool => $job->email === 'c@d.test' && $job->queue === 'default');
});

it('refuses to queue a job that is not queueable', function () {
    (new CreatePostFeature('x'))->runInQueue(RecordPostJob::class, ['slug']);
})->throws(Error::class, 'does not support queues');

it('refuses to queue an operation', function () {
    (new CreatePostFeature('x'))->runInQueue(new SlugifyTitleOperation('x'));
})->throws(Error::class, 'is an Operation and is not allowed to be queued yet');

it('resolves dispatchable units from class names or instances', function () {
    $feature = new CreatePostFeature('x');
    $instance = new RecordPostJob('slug');

    expect($feature->getDispatchableUnit($instance, []))->toBe($instance)
        ->and($feature->getDispatchableUnit(RecordPostJob::class, ['slug' => 'named']))->toEqual(new RecordPostJob('named'));
});

it('ships base classes for every unit type', function () {
    expect(new Request)->toBeInstanceOf(FormRequest::class)
        ->and(new QueueableJob)->toBeInstanceOf(ShouldQueue::class)
        ->and((new QueueableJob)->onQueue('mail')->queue)->toBe('mail')
        ->and(method_exists(Controller::class, 'validate'))->toBeTrue()
        ->and(method_exists(Feature::class, 'runInQueue'))->toBeTrue()
        ->and(method_exists(Operation::class, 'run'))->toBeTrue();
});
