<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Commands;

use Laranex\NextLaravel\Generators\FeatureGenerator;

class FeatureMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'next:feature
                        {feature : Feature}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new feature in a module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->generate(fn (): string => (new FeatureGenerator)->generate(
            $this->stringArgument('feature'),
            $this->stringArgument('module'),
            (bool) $this->option('force'),
        ));
    }
}
