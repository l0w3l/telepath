<?php

namespace Lowel\Telepath\Facades;

use Illuminate\Support\Facades\Facade;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;

/**
 * @mixin TelegramRouterInterface
 */
class Telepath extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TelegramRouterInterface::class;
    }
}
