<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons;

use Lowel\Telepath\Enums\ButtonStyleEnum;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

interface ButtonInterface
{
    public static function make(array $args = []): static;

    public function toButton(): InlineKeyboardButton|KeyboardButton;

    /**
     * @see ButtonStyleEnum
     */
    public function getStyle(): ?string;

    public function setStyle(ButtonStyleEnum $style): static;

    public function setStylePrimary(bool $condition = true): static;

    public function setStyleSuccess(bool $condition = true): static;

    public function setStyleDanger(bool $condition = true): static;

    public function resetStyle(): static;

    public function getText(): string;

    public function setText(string $text): static;

    public function getIconCustomEmojiId(): ?string;

    public function setCustomEmojiId(int|string $customEmojiId): static;
}
