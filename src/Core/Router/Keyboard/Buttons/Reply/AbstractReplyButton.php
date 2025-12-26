<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Reply;

use Closure;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\Type\KeyboardButton;

abstract class AbstractReplyButton implements ButtonInterface
{
    use InvokeAbleTrait;

    public function handle(): ?callable
    {
        return null;
    }

    public function pattern(): string
    {
        return $this->text();
    }

    abstract public function text(array $args = []): int|string|callable;

    public function toButton(array $args = []): KeyboardButton
    {
        $text = $this->text($args);

        if ($text instanceof Closure) {
            $text = $this::invokeCallableWithArgs($text, $args);
        }

        return new KeyboardButton(
            text: (string) $text
        );
    }

    public function resolve(TelegramRouterInterface $telegramRouter): void
    {
        if ($this->handle() === null) {
            return;
        }

        $pattern = "{$this->pattern()}";

        // reply keyboards works only with static content
        $telegramRouter->onMessage($this->handle()(...), $pattern);
    }
}
