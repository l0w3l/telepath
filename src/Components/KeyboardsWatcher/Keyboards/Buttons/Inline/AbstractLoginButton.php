<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;
use Vjik\TelegramBot\Api\Type\LoginUrl;

abstract class AbstractLoginButton implements ButtonInterface
{
    use InvokeAbleTrait;

    abstract public function url(array $args = []): int|string|callable;

    public function forwardText(array $args = []): null|int|string|callable
    {
        return null;
    }

    public function botUsername(array $args = []): null|int|string|callable
    {
        return null;
    }

    public function requestWriteAccess(array $args = []): null|int|string|callable
    {
        return null;
    }

    abstract public function text(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $url = $this->url($args);
        $forwardText = $this->text($args);
        $botUsername = $this->botUsername($args);
        $requestWriteAccess = $this->requestWriteAccess($args);

        if (is_callable($text)) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if (is_callable($forwardText)) {
            $forwardText = $this::invokeCallableWithArgs($forwardText);
        }
        if (is_callable($botUsername)) {
            $botUsername = $this::invokeCallableWithArgs($botUsername);
        }
        if (is_callable($requestWriteAccess)) {
            $requestWriteAccess = $this::invokeCallableWithArgs($requestWriteAccess);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            loginUrl: new LoginUrl(
                (string) $url,
                $forwardText === null ?: (string) $forwardText,
                $botUsername === null ?: (string) $botUsername,
                $requestWriteAccess === null ?: (string) $requestWriteAccess,
            ),
        );
    }
}
