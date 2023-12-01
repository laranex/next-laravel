<?php

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\OperationGenerator;

class OperationMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'next:operation
                        {operation : Operation}
                        {domain : Domain}
                        {--F|force : Overwrite existing files}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new operation in a domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $operation = $this->argument('operation');
            $domain = $this->argument('domain');
            $force = $this->option('force');

            $output = (new OperationGenerator())->generate($operation, $domain, $force);

            $this->printFileGeneratedOutput($output);
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
