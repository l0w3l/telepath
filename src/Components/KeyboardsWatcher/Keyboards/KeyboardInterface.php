<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonHandler;
use Vjik\TelegramBot\Api\Type\ForceReply;
use Vjik\TelegramBot\Api\Type\InlineKeyboardMarkup;
use Vjik\TelegramBot\Api\Type\ReplyKeyboardMarkup;
use Vjik\TelegramBot\Api\Type\ReplyKeyboardRemove;

/**
 * @template T of InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply
 */
interface KeyboardInterface
{
    /**
     * @return T
     */
    public static function build(array $args = []);

    /**
     * @return ButtonHandler[]
     */
    public static function handlers(): array;
}
