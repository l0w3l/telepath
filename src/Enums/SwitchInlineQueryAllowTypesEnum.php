<?php

declare(strict_types=1);

namespace Lowel\Telepath\Enums;

enum SwitchInlineQueryAllowTypesEnum
{
    case CURRENT;
    case USERS;
    case BOT;
    case GROUP;
    case CHANNEL;
}
