<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
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

        if ($text instanceof Closure) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if ($url instanceof Closure) {
            $url = $this::invokeCallableWithArgs($url);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            url: (string) $url,
        );
    }
}
