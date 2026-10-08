<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\RouteGenerator;

class RouteMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'next:route
                        {route : Route file name}
                        {versionOrDirectory? : API version or directory}
                        {--API|api : Generate an API route file}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $exitCode = $this->generate(fn (): string => (new RouteGenerator)->generate(
            $this->stringArgument('route'),
            $this->stringArgument('versionOrDirectory'),
            $this->option('api') ? 'api' : 'web',
            (bool) $this->option('force'),
        ));

        if (! config('next-laravel.enable_routes')) {
            $this->printDisableRoutesWarning();
        }

        return $exitCode;
    }
}
