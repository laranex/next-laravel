<?php

declare(strict_types=1);

namespace Laranex\NextLaravel\Cores;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Laranex\NextLaravel\Bus\ServesFeature;

class Controller
{
    use ServesFeature, ValidatesRequests;
}
