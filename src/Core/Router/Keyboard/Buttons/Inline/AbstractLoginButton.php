<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;
use Phptg\BotApi\Type\LoginUrl;

abstract class AbstractLoginButton extends AbstractInlineButton
{
    protected string $loginUrl = '';

    protected ?string $forwardText = null;

    protected ?string $botUsername = null;

    protected ?bool $requestWriteAccess = null;

    public function toButton(): InlineKeyboardButton|KeyboardButton
    {
        return new InlineKeyboardButton(
            text: $this->getText(),
            loginUrl: new LoginUrl(
                $this->getLoginUrl(),
                $this->getForwardText(),
                $this->getBotUsername(),
                $this->getRequestWriteAccess(),
            ),
            iconCustomEmojiId: $this->getIconCustomEmojiId(),
            style: $this->getStyle()
        );
    }

    public function getLoginUrl(): string
    {
        return $this->loginUrl;
    }

    public function setLoginUrl(string $loginUrl): static
    {
        $this->loginUrl = $loginUrl;

        return $this;
    }

    public function getForwardText(): ?string
    {
        return $this->forwardText;
    }

    public function setForwardText(string $forwardText): static
    {
        $this->forwardText = $forwardText;

        return $this;
    }

    public function getBotUsername(): ?string
    {
        return $this->botUsername;
    }

    public function setBotUsername(?string $botUsername): static
    {
        $this->botUsername = $botUsername;

        return $this;
    }

    public function getRequestWriteAccess(): ?bool
    {
        return $this->requestWriteAccess;
    }

    public function setRequestWriteAccess(?bool $requestWriteAccess): static
    {
        $this->requestWriteAccess = $requestWriteAccess;

        return $this;
    }
}
