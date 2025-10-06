<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
use Lowel\Telepath\Helpers\Hasher;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;

abstract class AbstractCallbackButton implements ButtonInterface
{
    use InvokeAbleTrait;

    protected bool $pay = false;

    abstract public function handle(): callable;

    abstract public function text(array $args = []): int|string|callable;

    public function callbackData(array $args = []): int|string|callable
    {
        return '';
    }

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $callbackData = $this->callbackData($args);

        if (is_callable($text)) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if (is_callable($callbackData)) {
            $callbackData = $this::invokeCallableWithArgs($callbackData);
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
