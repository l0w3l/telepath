<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline;

use Illuminate\Routing\Route;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Helpers\Hasher;
use Lowel\Telepath\Http\Middlewares\Buttons\AnswerCallbackQueryMiddleware;
use Phptg\BotApi\Type\InlineKeyboardButton;
use Phptg\BotApi\Type\KeyboardButton;

abstract class AbstractCallbackButton extends AbstractInlineButton
{
    protected string $callbackData = '';

    protected string $pattern = '.*';

    protected bool $pay = false;

    public function resolveCallbackData(): string
    {
        $callbackQuery = Extrasense::update()->callbackQuery;

        return str_replace($this->getCallbackDataId(), '', $callbackQuery->data);
    }

    public static function getCallbackDataId(): string
    {
        return Hasher::shortHash(static::class).':';
    }

    public function getCallbackData(): string
    {
        return $this->callbackData;
    }

    public function setCallbackData(string $callbackData): static
    {
        $this->callbackData = $callbackData;

        return $this;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): static
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function getPay(): bool
    {
        return $this->pay;
    }

    public function setPay(): static
    {
        $this->pay = true;

        return $this;
    }

    public function toButton(): InlineKeyboardButton|KeyboardButton
    {
        return new InlineKeyboardButton(
            text: $this->getText(),
            callbackData: $this->getCallbackDataId().$this->getCallbackData(),
            pay: $this->getPay(),
            iconCustomEmojiId: $this->getIconCustomEmojiId(),
            style: $this->getStyle()
        );
    }

    public function resolve(TelegramRouterInterface $telegramRouter): Route
    {
        $pattern = '^'.$this->getCallbackDataId().$this->getPattern().'$';

        return $telegramRouter->onCallbackQuery([static::class, 'handle'], $pattern)
            ->middleware(AnswerCallbackQueryMiddleware::class);
    }
}
