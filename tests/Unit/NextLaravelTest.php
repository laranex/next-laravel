<?php

declare(strict_types=1);

use Laranex\NextLaravel\Facades\NextLaravel as NextLaravelFacade;
use Laranex\NextLaravel\NextLaravel;

beforeEach(function () {
    $this->directory = $this->cleanup(sys_get_temp_dir().'/next-laravel-'.uniqid());

    $this->writeFile($this->directory.'/b.php', '<?php');
    $this->writeFile($this->directory.'/a.php', '<?php');
    $this->writeFile($this->directory.'/notes.txt', 'text');
    $this->writeFile($this->directory.'/nested/deep/c.php', '<?php');
});

it('lists every file of a directory recursively, sorted', function () {
    expect(NextLaravel::getAllFilesOfADirectory($this->directory))->toBe([
        $this->directory.'/a.php',
        $this->directory.'/b.php',
        $this->directory.'/nested/deep/c.php',
        $this->directory.'/notes.txt',
    ]);
});

it('filters the files by extension', function () {
    expect(NextLaravel::getAllFilesOfADirectory($this->directory, 'php'))->toBe([
        $this->directory.'/a.php',
        $this->directory.'/b.php',
        $this->directory.'/nested/deep/c.php',
    ])->and(NextLaravel::getAllFilesOfADirectory($this->directory, 'txt'))->toBe([$this->directory.'/notes.txt']);
});

it('returns an empty list for a missing directory', function () {
    expect(NextLaravel::getAllFilesOfADirectory($this->directory.'/missing'))->toBe([]);
});

it('is reachable through the facade', function () {
    expect(NextLaravelFacade::getAllFilesOfADirectory($this->directory, 'txt'))->toBe([$this->directory.'/notes.txt'])
        ->and(NextLaravelFacade::getFacadeRoot())->toBeInstanceOf(NextLaravel::class);
});
