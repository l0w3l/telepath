<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Closure;
use Lowel\Telepath\Enums\SwitchInlineQueryAllowTypesEnum;
use Lowel\Telepath\Helpers\Invoker;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\SwitchInlineQueryChosenChat;

abstract class AbstractSwitchInlineQueryButton extends AbstractInlineButton
{
    abstract public function switchInlineQuery(array $args = []): int|string|callable;

    /**
     * @return SwitchInlineQueryAllowTypesEnum[]
     */
    public function allow(): array
    {
        return [];
    }

    public function toButton(array $args = []): InlineKeyboardButton|KeyboardButton
    {
        $text = $this->text($args);
        $switchInlineQuery = $this->switchInlineQuery($args);

        if ($text instanceof Closure) {
            $text = Invoker::call($text);
        }
        if ($switchInlineQuery instanceof Closure) {
            $switchInlineQuery = Invoker::call($switchInlineQuery);
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
