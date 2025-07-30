<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

interface TelegramHandlerInterface
{
    public function __invoke(TelegramBotApi $telegram, Update $update): void;
}
