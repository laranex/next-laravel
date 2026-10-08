<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('posts', fn (): array => ['posts' => []])->name('fixtures.api.posts');
