<?php

declare(strict_types=1);

namespace Lowel\Telepath;

use Lowel\Telepath\Core\TelegramAppInterface;

interface TelegramAppFactoryInterface
{
    public function longPooling(): TelegramAppInterface;

    public function webhook(): TelegramAppInterface;
}
