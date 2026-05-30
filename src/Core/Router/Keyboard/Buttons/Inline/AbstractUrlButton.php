<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

abstract class AbstractUrlButton extends AbstractInlineButton
{
    protected string $url;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        return new InlineKeyboardButton(
            text: $this->getText(),
            url: $this->getUrl(),
            iconCustomEmojiId: $this->getIconCustomEmojiId(),
            style: $this->getStyle()
        );
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
