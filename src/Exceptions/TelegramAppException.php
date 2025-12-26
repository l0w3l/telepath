<?php

namespace Lowel\Telepath\Exceptions;

use Exception;
use Phptg\BotApi\FailResult;
use Psr\Http\Message\ServerRequestInterface;

class TelegramAppException extends Exception
{
    public FailResult|ServerRequestInterface $failResult;

    public function __construct(FailResult|ServerRequestInterface $failResult, ?\Throwable $previous = null)
    {
        $this->failResult = $failResult;

        parent::__construct('Failed to retrieve updates', previous: $previous);
    }
}
