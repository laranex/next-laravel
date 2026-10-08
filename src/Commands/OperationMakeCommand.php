<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\OperationGenerator;

class OperationMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'next:operation
                        {operation : Operation}
                        {domain : Domain}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new operation in a domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->generate(fn (): string => (new OperationGenerator)->generate(
            $this->stringArgument('operation'),
            $this->stringArgument('domain'),
            (bool) $this->option('force'),
        ));
    }
}
