<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades;

use Illuminate\Support\Facades\Facade;
use Lowel\Telepath\Components\ExceptionHandler\ExceptionHandlerInterface;

/**
 * @mixin ExceptionHandlerInterface
 */
class Paranormal extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ExceptionHandlerInterface::class;
    }
}
