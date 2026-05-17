<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Lowel\Telepath\Enums\SwitchInlineQueryAllowTypesEnum;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\SwitchInlineQueryChosenChat;

abstract class AbstractSwitchInlineQueryButton extends AbstractInlineButton
{
    /** @var string[] */
    protected array $allowed = [];

    protected string $switchInlineQueryData = '';

    public function toButton(): InlineKeyboardButton|KeyboardButton
    {
        $allowed = $this->getAllowed();

        if (empty($allowed)) {
            return new InlineKeyboardButton(
                text: $this->getText(),
                switchInlineQuery: $this->getSwitchInlineQueryData(),
                iconCustomEmojiId: $this->getIconCustomEmojiId(),
                style: $this->getStyle()
            );
        }
        if (in_array(SwitchInlineQueryAllowTypesEnum::CURRENT, $allowed)) {
            return new InlineKeyboardButton(
                text: $this->getText(),
                switchInlineQueryCurrentChat: $this->getSwitchInlineQueryData(),
                iconCustomEmojiId: $this->getIconCustomEmojiId(),
                style: $this->getStyle()
            );
        } else {
            return new InlineKeyboardButton(
                text: $this->getText(),
                switchInlineQueryChosenChat: new SwitchInlineQueryChosenChat(
                    query: $this->getSwitchInlineQueryData(),
                    allowUserChats: in_array(SwitchInlineQueryAllowTypesEnum::USERS, $allowed),
                    allowBotChats: in_array(SwitchInlineQueryAllowTypesEnum::BOT, $allowed),
                    allowGroupChats: in_array(SwitchInlineQueryAllowTypesEnum::GROUP, $allowed),
                    allowChannelChats: in_array(SwitchInlineQueryAllowTypesEnum::CHANNEL, $allowed),
                ),
                iconCustomEmojiId: $this->getIconCustomEmojiId(),
                style: $this->getStyle()
            );
        }
    }

    public function getAllowed(): array
    {
        return $this->allowed;
    }

    public function setAllowed(array $allowed): static
    {
        $this->allowed = $allowed;

        return $this;
    }

    public function getSwitchInlineQueryData(): string
    {
        return $this->switchInlineQueryData;
    }

    public function setSwitchInlineQueryData(string $switchInlineQueryData): static
    {
        $this->switchInlineQueryData = $switchInlineQueryData;

        return $this;
    }
}
