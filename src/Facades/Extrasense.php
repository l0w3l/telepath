<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades;

use Illuminate\Support\Facades\Facade;
use Lowel\Telepath\Components\Context\ContextInterface;

/**
 * @mixin ContextInterface
 */
class Extrasense extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ContextInterface::class;
    }
}
