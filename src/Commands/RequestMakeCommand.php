<?php

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\RequestGenerator;

class RequestMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'next:request
                        {request : Request}
                        {domain : Domain}
                        {--F|force : Overwrite existing files}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new request in a domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $request = $this->argument('request');
            $domain = $this->argument('domain');
            $force = $this->option('force');

            $output = (new RequestGenerator())->generate($request, $domain, $force);

            $this->printFileGeneratedOutput($output);
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
