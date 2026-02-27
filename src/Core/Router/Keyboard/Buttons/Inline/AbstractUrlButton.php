<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Helpers\Invoker;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

abstract class AbstractUrlButton extends AbstractInlineButton
{
    abstract public function url(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $url = $this->url($args);

        if ($text instanceof Closure) {
            $text = Invoker::call($text);
        }
        if ($url instanceof Closure) {
            $url = Invoker::call($url);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            url: (string) $url,
            style: $this->style(),
            iconCustomEmojiId: $this->iconCustomEmojiId()
        );
    }
}
