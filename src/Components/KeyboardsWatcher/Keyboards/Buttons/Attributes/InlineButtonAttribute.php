<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Attributes;

use Attribute;
use Lowel\Telepath\Traits\HashAbleTrait;
use Vjik\TelegramBot\Api\Type\CopyTextButton;
use Vjik\TelegramBot\Api\Type\Game\CallbackGame;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\LoginUrl;
use Vjik\TelegramBot\Api\Type\SwitchInlineQueryChosenChat;
use Vjik\TelegramBot\Api\Type\WebAppInfo;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class InlineButtonAttribute
{
    use HashAbleTrait;

    /**
     * Contains configuration for inline keyboard button
     *
     * @link InlineKeyboardButton
     *
     * @param  'row'|'col'  $direction
     */
    public function __construct(
        public string $text = 'button',
        public string $direction = 'col',
        public ?string $url = null,
        public ?string $callbackData = null,
        public ?WebAppInfo $webApp = null,
        public ?LoginUrl $loginUrl = null,
        public ?string $switchInlineQuery = null,
        public ?string $switchInlineQueryCurrentChat = null,
        public ?SwitchInlineQueryChosenChat $switchInlineQueryChosenChat = null,
        public ?CallbackGame $callbackGame = null,
        public ?bool $pay = null,
        public ?CopyTextButton $copyText = null,
    ) {}

    public function toButton(string $methodName, ?string $dynamicText = null): InlineKeyboardButton
    {
        return new InlineKeyboardButton(
            $dynamicText ?? $this->text,
            $this->url,
            $this->getCallbackData($methodName),
            $this->webApp,
            $this->loginUrl,
            $this->switchInlineQuery,
            $this->switchInlineQueryCurrentChat,
            $this->switchInlineQueryChosenChat,
            $this->callbackGame,
            $this->pay,
            $this->copyText
        );
    }

    public function getCallbackData(string $methodName): string
    {
        return match ($this->copyText || $this->switchInlineQuery || $this->switchInlineQueryCurrentChat || $this->webApp || $this->loginUrl || $this->url) {
            true => null,
            false => $this->callbackData ?? (self::shortHash(self::class).':'.self::shortHash($methodName)),
        };
    }
}
