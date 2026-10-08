<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
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
    public function handle(RouteGenerator $generator, ConfigRepository $config): int
    {
        $exitCode = $this->generate(fn (): string => $generator->generate(
            $this->stringArgument('route'),
            $this->stringArgument('versionOrDirectory'),
            (bool) $this->option('api') ? 'api' : 'web',
            (bool) $this->option('force'),
        ));

        if (! (bool) $config->get('next-laravel.enable_routes', true)) {
            $this->printDisableRoutesWarning();
        }

        return $exitCode;
    }
}
