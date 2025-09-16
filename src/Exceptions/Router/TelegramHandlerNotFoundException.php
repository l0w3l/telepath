<?php

namespace Lowel\Telepath\Exceptions\Router;

use Exception;
use Vjik\TelegramBot\Api\Type\Update\Update;

class TelegramHandlerNotFoundException extends Exception
{
    public Update $update;

    public function __construct(Update $update)
    {
        $this->update = $update;

        parent::__construct('Update not found exception');
    }
}
