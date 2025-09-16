<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Attributes;

use Attribute;
use Vjik\TelegramBot\Api\Type\CopyTextButton;
use Vjik\TelegramBot\Api\Type\Game\CallbackGame;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\LoginUrl;
use Vjik\TelegramBot\Api\Type\SwitchInlineQueryChosenChat;
use Vjik\TelegramBot\Api\Type\WebAppInfo;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class InlineButtonAttribute
{
    /**
     * Contains configuration for inline keyboard button
     *
     * @link InlineKeyboardButton
     *
     * @param  'row'|'col'  $direction
     */
    public function __construct(
        public string $text,
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
}
