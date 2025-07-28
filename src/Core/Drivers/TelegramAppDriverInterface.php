<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers;

use Lowel\Telepath\Commands\Exceptions\TelegramAppException;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerCollectionInterface;
use Vjik\TelegramBot\Api\TelegramBotApi;

interface TelegramAppDriverInterface
{
    /**
     * Run the driver to process incoming updates.
     *
     * @param  TelegramBotApi  $telegramBotApi  The Telegram Bot API instance.
     * @param  TelegramHandlerCollectionInterface  $handlersCollection  The collection of handlers to process updates.
     *
     * @throws TelegramAppException
     */
    public function proceed(TelegramBotApi $telegramBotApi, TelegramHandlerCollectionInterface $handlersCollection): void;
}
