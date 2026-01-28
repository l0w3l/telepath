<?php

namespace Lowel\Telepath\Exceptions;

use Exception;
use Phptg\BotApi\FailResult;

class TelegramAppException extends Exception
{
    public FailResult|string $failResult;

    public function __construct(FailResult|string $failResult, ?\Throwable $previous = null)
    {
        $this->failResult = $failResult;

        parent::__construct('Failed to retrieve updates', previous: $previous);
    }
}
