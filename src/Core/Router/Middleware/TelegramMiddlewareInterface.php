<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Middleware;

use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

interface TelegramMiddlewareInterface
{
    public function __invoke(TelegramBotApi $telegram, Update $update, callable $callback): void;
}
