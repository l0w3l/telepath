<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonInterface;
use Vjik\TelegramBot\Api\Type\ForceReply;
use Vjik\TelegramBot\Api\Type\InlineKeyboardMarkup;
use Vjik\TelegramBot\Api\Type\ReplyKeyboardMarkup;
use Vjik\TelegramBot\Api\Type\ReplyKeyboardRemove;

interface KeyboardBuilderInterface
{
    public function build(array $args = []): InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply;

    /**
     * @return ButtonInterface[][]
     */
    public function toArray(): array;
}
