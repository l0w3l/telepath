<?php

declare(strict_types=1);

namespace Lowel\Telepath\Enums;

use Phptg\BotApi\Type\Chat;

enum ChatTypesEnum: string
{
    case PRIVATE = 'private';
    case GROUP = 'group';
    case SUPERGROUP = 'supergroup';
    case CHANNEL = 'channel';

    public static function isPrivate(Chat $chat): bool
    {
        return $chat->type === self::PRIVATE->value;
    }

    public static function isGroup(Chat $chat): bool
    {
        return $chat->type === self::GROUP->value;
    }

    public static function isSupergroup(Chat $chat): bool
    {
        return $chat->type === self::SUPERGROUP->value;
    }

    public static function isChannel(Chat $chat): bool
    {
        return $chat->type === self::CHANNEL->value;
    }
}
