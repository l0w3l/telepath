<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
use Lowel\Telepath\Enums\SwitchInlineQueryAllowTypesEnum;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\KeyboardButton;
use Vjik\TelegramBot\Api\Type\SwitchInlineQueryChosenChat;

abstract class AbstractSwitchInlineQueryButton implements ButtonInterface
{
    use InvokeAbleTrait;

    abstract public function switchInlineQuery(array $args = []): int|string|callable;

    /**
     * @return SwitchInlineQueryAllowTypesEnum[]
     */
    public function allow(): array
    {
        return [];
    }

    abstract public function text(array $args = []): int|string|callable;

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $switchInlineQuery = $this->switchInlineQuery($args);

        if (is_callable($text)) {
            $text = $this::invokeCallableWithArgs($text);
        }
        if (is_callable($switchInlineQuery)) {
            $switchInlineQuery = $this::invokeCallableWithArgs($switchInlineQuery);
        }

        $allowed = $this->allow();

        if (empty($allowed)) {
            return new InlineKeyboardButton(
                text: (string) $text,
                switchInlineQuery: (string) $switchInlineQuery,
            );
        }
        if (in_array(SwitchInlineQueryAllowTypesEnum::CURRENT, $allowed)) {
            return new InlineKeyboardButton(
                text: (string) $text,
                switchInlineQueryCurrentChat: (string) $switchInlineQuery,
            );
        } else {
            return new InlineKeyboardButton(
                text: (string) $text,
                switchInlineQueryChosenChat: new SwitchInlineQueryChosenChat(
                    query: (string) $switchInlineQuery,
                    allowUserChats: in_array(SwitchInlineQueryAllowTypesEnum::USERS, $allowed),
                    allowBotChats: in_array(SwitchInlineQueryAllowTypesEnum::BOT, $allowed),
                    allowGroupChats: in_array(SwitchInlineQueryAllowTypesEnum::GROUP, $allowed),
                    allowChannelChats: in_array(SwitchInlineQueryAllowTypesEnum::CHANNEL, $allowed),
                )
            );
        }
    }
}
