<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Helpers\Invoker;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\WebAppInfo;

abstract class AbstractWebAppButton extends AbstractInlineButton
{
    abstract public function url(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $webAppUrl = $this->url($args);

        if ($text instanceof Closure) {
            $text = Invoker::call($text);
        }
        if (is_callable($webAppUrl)) {
            $webAppUrl = Invoker::call($webAppUrl);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            webApp: new WebAppInfo((string) $webAppUrl),
            style: $this->style($args),
            iconCustomEmojiId: $this->iconCustomEmojiId($args)
        );
    }
}
