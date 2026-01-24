<?php

declare(strict_types=1);

namespace Lowel\Telepath\Helpers;

use Illuminate\Support\Facades\App;

class Invoker
{
    public static function call(callable $callable, array $args = []): mixed
    {
        return App::call($callable, $args);
    }
}
