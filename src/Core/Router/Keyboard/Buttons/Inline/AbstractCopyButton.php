<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\Type\CopyTextButton;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;

abstract class AbstractCopyButton implements ButtonInterface
{
    use InvokeAbleTrait;

    abstract public function copyText(array $args = []): int|string|callable;

    abstract public function text(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $copyText = $this->copyText($args);

        if ($text instanceof Closure) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if ($copyText instanceof Closure) {
            $copyText = $this::invokeCallableWithArgs($copyText);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            copyText: new CopyTextButton((string) $copyText),
        );
    }
}
