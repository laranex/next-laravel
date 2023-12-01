<?php

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\ControllerGenerator;

class ControllerMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'next:controller
                        {controller : Controller}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new controller in a module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $feature = $this->argument('controller');
            $module = $this->argument('module');
            $force = $this->option('force');

            $output = (new ControllerGenerator())->generate($feature, $module, $force);

            $this->printFileGeneratedOutput($output);
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
