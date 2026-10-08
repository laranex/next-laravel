<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\RequestGenerator;

class RequestMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'next:request
                        {request : Request}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request in a module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->generate(fn (): string => (new RequestGenerator)->generate(
            $this->stringArgument('request'),
            $this->stringArgument('module'),
            (bool) $this->option('force'),
        ));
    }
}
