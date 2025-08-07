<?php

declare(strict_types=1);

namespace Lowel\Telepath\Enums;

use Vjik\TelegramBot\Api\Type\Message;

enum ChatTypesEnum: string
{
    case PRIVATE = 'private';
    case GROUP = 'group';
    case SUPERGROUP = 'supergroup';
    case CHANNEL = 'channel';

    public static function isPrivate(Message $message): bool
    {
        return $message->chat->type === self::PRIVATE->value;
    }

    public static function isGroup(Message $message): bool
    {
        return $message->chat->type === self::GROUP->value;
    }

    public static function isSupergroup(Message $message): bool
    {
        return $message->chat->type === self::SUPERGROUP->value;
    }

    public static function isChannel(Message $message): bool
    {
        return $message->chat->type === self::CHANNEL->value;
    }
}
