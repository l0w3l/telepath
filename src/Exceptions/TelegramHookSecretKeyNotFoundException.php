<?php

declare(strict_types=1);

namespace Lowel\Telepath\Exceptions;

use RuntimeException;

class TelegramHookSecretKeyNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Telegram hook secret key not found. Please run "php artisan telepath:key:generate".');
    }
}
