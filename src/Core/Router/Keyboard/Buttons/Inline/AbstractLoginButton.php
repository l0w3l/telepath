<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Helpers\Invoker;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\LoginUrl;

abstract class AbstractLoginButton extends AbstractInlineButton
{
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
            $text = Invoker::call($text);
        }
        if ($forwardText instanceof Closure) {
            $forwardText = Invoker::call($forwardText);
        }
        if ($botUsername instanceof Closure) {
            $botUsername = Invoker::call($botUsername);
        }
        if ($requestWriteAccess instanceof Closure) {
            $requestWriteAccess = Invoker::call($requestWriteAccess);
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
