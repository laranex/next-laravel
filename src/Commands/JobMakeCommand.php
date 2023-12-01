<?php

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\JobGenerator;

class JobMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'next:job
                        {job : Job}
                        {module : Module}
                        {--Q|queue : Make the job queueable}
                        {--F|force : Overwrite existing files}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new job in a module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $job = $this->argument('job');
            $module = $this->argument('module');
            $queueable = $this->option('queue');
            $force = $this->option('force');

            $output = (new JobGenerator())->generate($job, $module, $queueable, $force);

            $this->printFileGeneratedOutput($output);
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
