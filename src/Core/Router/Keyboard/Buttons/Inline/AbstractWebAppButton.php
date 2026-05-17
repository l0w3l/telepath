<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\WebAppInfo;

abstract class AbstractWebAppButton extends AbstractInlineButton
{
    protected string $webAppUrl = '';

    public function toButton(): InlineKeyboardButton|KeyboardButton
    {
        return new InlineKeyboardButton(
            text: $this->getText(),
            webApp: new WebAppInfo($this->getWebAppUrl()),
            style: $this->getStyle(),
            iconCustomEmojiId: $this->getIconCustomEmojiId()
        );
    }

    public function getWebAppUrl(): string
    {
        return $this->webAppUrl;
    }

    public function setWebAppUrl(string $webAppUrl): static
    {
        $this->webAppUrl = $webAppUrl;

        return $this;
    }
}
