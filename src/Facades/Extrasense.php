<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades;

use Illuminate\Support\Facades\Facade;
use Lowel\Telepath\Core\GlobalAppContext\GlobalAppContextInterface;

/**
 * @mixin GlobalAppContextInterface
 */
class Extrasense extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GlobalAppContextInterface::class;
    }
}
