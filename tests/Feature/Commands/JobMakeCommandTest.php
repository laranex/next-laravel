<?php

declare(strict_types=1);

beforeEach(fn () => $this->cleanup(app_path('Modules/MailModule')));

it('generates a plain job inside the module', function () {
    $this->artisan('next:job', ['job' => 'sendWelcomeEmail', 'module' => 'mail'])
        ->expectsOutputToContain('Modules/MailModule/Jobs/SendWelcomeEmailJob.php has been successfully generated!')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/MailModule/Jobs/SendWelcomeEmailJob.php')))
        ->toContain('namespace App\Modules\MailModule\Jobs;')
        ->toContain('use Laranex\NextLaravel\Cores\Job;')
        ->toContain('class SendWelcomeEmailJob extends Job');
});

it('generates a queueable job with --queue', function () {
    $this->artisan('next:job', ['job' => 'sendWelcomeEmail', 'module' => 'mail', '--queue' => true])->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/MailModule/Jobs/SendWelcomeEmailJob.php')))
        ->toContain('use Laranex\NextLaravel\Cores\QueueableJob;')
        ->toContain('class SendWelcomeEmailJob extends QueueableJob');
});

it('fails when the job already exists', function () {
    $this->writeFile(app_path('Modules/MailModule/Jobs/SendWelcomeEmailJob.php'), 'original');

    $this->artisan('next:job', ['job' => 'sendWelcomeEmail', 'module' => 'mail'])
        ->expectsOutputToContain('SendWelcomeEmailJob.php already exists!')
        ->assertExitCode(1);

    $this->artisan('next:job', ['job' => 'sendWelcomeEmail', 'module' => 'mail', '-Q' => true, '-F' => true])->assertExitCode(0);

    expect(file_get_contents(app_path('Modules/MailModule/Jobs/SendWelcomeEmailJob.php')))->toContain('extends QueueableJob');
});
