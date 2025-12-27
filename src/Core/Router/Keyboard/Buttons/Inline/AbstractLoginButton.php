<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\LoginUrl;

abstract class AbstractLoginButton extends AbstractInlineButton
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

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $url = $this->url($args);
        $forwardText = $this->text($args);
        $botUsername = $this->botUsername($args);
        $requestWriteAccess = $this->requestWriteAccess($args);

        if ($text instanceof Closure) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if ($forwardText instanceof Closure) {
            $forwardText = $this::invokeCallableWithArgs($forwardText);
        }
        if ($botUsername instanceof Closure) {
            $botUsername = $this::invokeCallableWithArgs($botUsername);
        }
        if ($requestWriteAccess instanceof Closure) {
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
