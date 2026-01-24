<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Helpers\Hasher;
use Lowel\Telepath\Helpers\Invoker;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

abstract class AbstractCallbackButton extends AbstractInlineButton
{
    protected bool $pay = false;

    abstract public function handle(): callable;

    public function callbackData(array $args = []): int|string|callable
    {
        return '';
    }

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $callbackData = $this->callbackData($args);

        if ($text instanceof Closure) {
            $text = Invoker::call($text);
        }
        if ($callbackData instanceof Closure) {
            $callbackData = Invoker::call($callbackData);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            callbackData: $this->callbackDataId().$callbackData,
            pay: $this->pay,
        );
    }

    public function callbackDataId(): string
    {
        return Hasher::shortHash(static::class).':';
    }
}
