<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core;

use Lowel\Telepath\Exceptions\TelegramAppException;

interface TelegramAppInterface
{
    /**
     * Starts the Telegram application.
     *
     * This method initializes the Telegram bot API and begins processing updates
     * according to the configured handlers.
     *
     * @throws TelegramAppException
     */
    public function start(): void;
}
