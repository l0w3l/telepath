<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\ExceptionHandler;

use Closure;
use Throwable;
use Vjik\TelegramBot\Api\Type\Update\Update;

interface ExceptionHandlerInterface
{
    /**
     * @param  Closure(Update $update, Throwable $e, mixed $previus): mixed  $callback
     */
    public function wrap(Closure $callback): void;

    public function catch(Update $update, Throwable $e): void;

    public function reset(): void;
}
