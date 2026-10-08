<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('posts', fn (): string => 'web posts')->name('fixtures.web.posts');
