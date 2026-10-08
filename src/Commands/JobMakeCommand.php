<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\JobGenerator;

class JobMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'next:job
                        {job : Job}
                        {module : Module}
                        {--Q|queue : Make the job queueable}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job in a module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->generate(fn (): string => (new JobGenerator)->generate(
            $this->stringArgument('job'),
            $this->stringArgument('module'),
            (bool) $this->option('queue'),
            (bool) $this->option('force'),
        ));
    }
}
