<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Helpers\Invoker;
use Phptg\BotApi\Type\CopyTextButton;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

abstract class AbstractCopyButton extends AbstractInlineButton
{
    abstract public function copyText(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $copyText = $this->copyText($args);

        if ($text instanceof Closure) {
            $text = Invoker::call($text);
        }
        if ($copyText instanceof Closure) {
            $copyText = Invoker::call($copyText);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            copyText: new CopyTextButton((string) $copyText),
            style: $this->style(),
            iconCustomEmojiId: $this->iconCustomEmojiId()
        );
    }
}
