<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\ControllerGenerator;

class ControllerMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'next:controller
                        {controller : Controller}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller in a module';

    /**
     * Execute the console command.
     */
    public function handle(ControllerGenerator $generator): int
    {
        return $this->generate(fn (): string => $generator->generate(
            $this->stringArgument('controller'),
            $this->stringArgument('module'),
            (bool) $this->option('force'),
        ));
    }
}
