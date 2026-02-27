<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons;

use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

interface ButtonInterface
{
    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton;

    public function text(array $args = []): int|string|callable;

    public function resolve(TelegramRouterInterface $telegramRouter): void;

    public function iconCustomEmojiId(array $args = []): ?string;

    /**
     * @see Lowel\Telepath\Enums\ButtonStyleEnum::class
     */
    public function style(array $args = []): ?string;
}
