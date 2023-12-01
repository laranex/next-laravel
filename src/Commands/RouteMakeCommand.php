<?php

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\RouteGenerator;

class RouteMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'next:route
                        {route : Route file name}
                        {versionOrDirectory? : API version or Directory}
                        {--API|api : Generate API route file}
                        {--F|force : Overwrite existing files}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new route file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $route = $this->argument('route');
            $versionOrDirectory = $this->argument('versionOrDirectory') ?? '';
            $routeFileType = $this->option('api') ? 'api' : 'web';
            $force = $this->option('force');

            $output = (new RouteGenerator())->generate($route, $versionOrDirectory, $routeFileType, $force);

            $this->printFileGeneratedOutput($output);
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        if (! config('next-laravel.enable_routes')) {
            $this->printDisableRoutesWarning();
        }

        return 0;
    }
}
