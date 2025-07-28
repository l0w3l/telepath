<?php

namespace Lowel\Telepath\Exceptions\Router;

use Exception;
use Lowel\Telepath\Enums\UpdateTypeEnum;

class TelegramHandlerNotFoundException extends Exception
{
    public function __construct(string|UpdateTypeEnum $target = '.')
    {
        if (is_string($target)) {
            parent::__construct("Handler not found by text `{$target}`");
        } else {
            parent::__construct("Handler not found by update type `{$target->value}`");
        }
    }
}
