<?php

declare(strict_types=1);

namespace Lowel\Telepath\Exceptions\Config;

use RuntimeException;

class TelepathProfileNotFoundException extends RuntimeException
{
    public function __construct(string $profileKey)
    {
        $availableProfileKeys = array_keys(config('telepath.profiles'));

        $message = "Telepath profile not found by given key ('{$profileKey}'). Available list: ".implode(', ', $availableProfileKeys);

        parent::__construct($message);
    }
}
