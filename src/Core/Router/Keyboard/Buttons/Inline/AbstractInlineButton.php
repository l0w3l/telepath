<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;
use Vjik\TelegramBot\Api\Type\Update\Update;

abstract class AbstractInlineButton implements ButtonInterface
{
    abstract public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton;

    abstract public function text(array $args = []): int|string|callable;

    public function resolve(TelegramRouterInterface $telegramRouter): void
    {
        if ($this instanceof AbstractCallbackButton) {
            $pattern = "/^{$this->callbackDataId()}.*$/";

            $telegramRouter->onCallbackQuery($this->handle()(...), $pattern)
                ->middleware(function (TelegramBotApi $api, Update $update, callable $next) {
                    $next();
                    $api->answerCallbackQuery($update->callbackQuery->id);
                });
        }
    }
}
