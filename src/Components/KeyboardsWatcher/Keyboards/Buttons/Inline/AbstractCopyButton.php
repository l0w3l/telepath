<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
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

        if (is_callable($text)) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if (is_callable($copyText)) {
            $copyText = $this::invokeCallableWithArgs($copyText);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            copyText: new CopyTextButton((string) $copyText),
        );
    }
}
