<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades;

use Illuminate\Support\Facades\Facade;
use Phptg\BotApi\TelegramBotApi;

/**
 * @mixin TelegramBotApi
 */
class SpiritBox extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TelegramBotApi::class;
    }
}
