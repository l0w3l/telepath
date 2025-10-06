<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons;

use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;

/**
 * @method string text() - DI supported text handler
 */
interface ButtonInterface
{
    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton;
}
