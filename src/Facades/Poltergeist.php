<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades;

use Illuminate\Support\Facades\Facade;
use Lowel\Telepath\Components\KeyboardsWatcher\KeyboardsWatcherInterface;

class Poltergeist extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return KeyboardsWatcherInterface::class;
    }
}
