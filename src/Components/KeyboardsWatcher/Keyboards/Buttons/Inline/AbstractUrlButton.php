<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;

abstract class AbstractUrlButton implements ButtonInterface
{
    use InvokeAbleTrait;

    abstract public function url(array $args = []): int|string|callable;

    abstract public function text(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $url = $this->url($args);

        if (is_callable($text)) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if (is_callable($url)) {
            $url = $this::invokeCallableWithArgs($url);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            url: (string) $url,
        );
    }
}
