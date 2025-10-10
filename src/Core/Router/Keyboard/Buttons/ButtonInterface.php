<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons;

use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;

interface ButtonInterface
{
    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton;

    public function text(array $args = []): int|string|callable;

    public function resolve(TelegramRouterInterface $telegramRouter): void;
}
