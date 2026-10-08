<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Tests\Concerns;

/**
 * Marks tests that write to, or scan, the Testbench skeleton paths shared by
 * every parallel worker (routes/web, routes/api, resources/stubs/vendor).
 * They hold an exclusive lock; every other test holds a shared one.
 */
trait TouchesSharedSkeleton {}
