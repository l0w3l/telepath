<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Phptg\BotApi\Type\CopyTextButton;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

abstract class AbstractCopyButton extends AbstractInlineButton
{
    protected string $copyTextData = '';

    public function getCopyText(): string
    {
        return $this->copyTextData;
    }

    public function setCopyText(string $text): static
    {
        $this->copyTextData = $text;

        return $this;
    }

    public function toButton(): InlineKeyboardButton|KeyboardButton
    {
        return new InlineKeyboardButton(
            text: $this->getCopyText(),
            copyText: new CopyTextButton($this->getCopyText()),
            iconCustomEmojiId: $this->getIconCustomEmojiId(),
            style: $this->getStyle()
        );
    }
}
