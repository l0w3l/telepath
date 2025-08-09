<?php

declare(strict_types=1);

namespace Lowel\Telepath\Enums;

enum TelegramPromiseStatusEnum: string
{
    case PENDING = 'pending';
    case FULFILLED = 'fulfilled';
    case REJECTED = 'rejected';
}
