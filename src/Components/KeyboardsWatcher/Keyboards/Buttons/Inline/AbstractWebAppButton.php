<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;
use Vjik\TelegramBot\Api\Type\WebAppInfo;

abstract class AbstractWebAppButton implements ButtonInterface
{
    use InvokeAbleTrait;

    abstract public function url(array $args = []): int|string|callable;

    abstract public function text(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $webAppUrl = $this->url($args);

        if (is_callable($text)) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if (is_callable($webAppUrl)) {
            $webAppUrl = $this::invokeCallableWithArgs($webAppUrl);
        }

        return new InlineKeyboardButton(
            text: (string) $text,
            webApp: new WebAppInfo((string) $webAppUrl),
        );
    }
}
