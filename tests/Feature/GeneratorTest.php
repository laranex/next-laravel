<?php

declare(strict_types=1);

use Laranex\NextLaravel\Generators\ControllerGenerator;
use Laranex\NextLaravel\Generators\FeatureGenerator;
use Laranex\NextLaravel\Generators\JobGenerator;
use Laranex\NextLaravel\Generators\OperationGenerator;
use Laranex\NextLaravel\Generators\RequestGenerator;
use Laranex\NextLaravel\Generators\RouteGenerator;

beforeEach(function () {
    $this->directory = $this->cleanup(sys_get_temp_dir().'/next-laravel-'.uniqid());
});

it('replaces placeholders and normalizes namespace separators', function () {
    $content = (new ControllerGenerator)->replacePlaceholders('namespace {{namespace}}; class {{controller}} {}', [
        'namespace' => 'App/Modules/BlogModule/Http/Controllers',
        'controller' => 'PostController',
    ]);

    expect($content)->toBe('namespace App\Modules\BlogModule\Http\Controllers; class PostController {}');
});

it('writes files into directories it creates on demand', function () {
    (new FeatureGenerator)->generateFile($this->directory.'/a/b', $this->directory.'/a/b/File.php', '<?php // generated');

    expect(file_get_contents($this->directory.'/a/b/File.php'))->toBe('<?php // generated');
});

it('refuses to overwrite an existing file unless forced', function () {
    $this->writeFile($this->directory.'/Existing.php', '');
    $generator = new OperationGenerator;

    $generator->throwIfFileExists($this->directory.'/Missing.php');
    $generator->throwIfFileExists($this->directory.'/Existing.php', true);

    expect(fn () => $generator->throwIfFileExists($this->directory.'/Existing.php'))
        ->toThrow(Exception::class, 'Existing.php already exists!');
});

it('reads the bundled stubs', function () {
    expect((new ControllerGenerator)->getStubContents())->toContain('extends Controller')
        ->and((new FeatureGenerator)->getStubContents())->toContain('extends Feature')
        ->and((new OperationGenerator)->getStubContents())->toContain('extends Operation')
        ->and((new RequestGenerator)->getStubContents())->toContain('extends Request')
        ->and((new JobGenerator)->getStubContents())->toContain('extends Job')
        ->and((new JobGenerator)->getStubContents(true))->toContain('extends QueueableJob')
        ->and((new RouteGenerator)->getStubContents())->toContain("Route::prefix('{{prefix}}')");
});

it('rejects names containing a path separator', function (string $name) {
    expect(fn () => (new FeatureGenerator)->ensureNameIsNotNested($name, 'feature'))
        ->toThrow(InvalidArgumentException::class, "The feature name [$name] must not contain \"/\" or \"\\\". Nested names are not supported.");
})->with(['Blog/createPost', 'Blog\\CreatePost']);
