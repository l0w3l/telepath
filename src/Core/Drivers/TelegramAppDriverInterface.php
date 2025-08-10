<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Generator;
use Lowel\Telepath\Commands\Exceptions\TelegramAppException;
use Vjik\TelegramBot\Api\TelegramBotApi;

interface TelegramAppDriverInterface
{
    /**
     * Run the driver to process incoming updates.
     *
     * @param  TelegramBotApi  $telegramBotApi  The Telegram Bot API instance.
     *
     * @throws TelegramAppException
     */
    public function proceed(TelegramBotApi $telegramBotApi): Generator;
}
