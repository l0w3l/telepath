<?php

declare(strict_types=1);

namespace Lowel\Telepath\Enums;

enum AsyncRequestEnum: string
{
    case PENDING = 'pending';
    case OK = 'ok';
    case ERROR = 'error';
    case EXPIRED = 'expired';
}
