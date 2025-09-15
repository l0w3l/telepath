<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Generator;
use Lowel\Telepath\Exceptions\TelegramAppException;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

interface TelegramAppDriverInterface
{
    /**
     * Run the driver to process incoming updates.
     *
     * @param  TelegramBotApi  $telegramBotApi  The Telegram Bot API instance.
     * @return Generator<int, Update>
     *
     * @throws TelegramAppException
     */
    public function proceed(TelegramBotApi $telegramBotApi): Generator;
}
