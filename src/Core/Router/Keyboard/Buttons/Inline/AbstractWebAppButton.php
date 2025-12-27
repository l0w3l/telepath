<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\WebAppInfo;

abstract class AbstractWebAppButton extends AbstractInlineButton
{
    use InvokeAbleTrait;

    abstract public function url(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $webAppUrl = $this->url($args);

        if ($text instanceof Closure) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if (is_callable($webAppUrl)) {
            $webAppUrl = $this::invokeCallableWithArgs($webAppUrl);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            webApp: new WebAppInfo((string) $webAppUrl),
        );
    }
}
