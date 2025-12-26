<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\ExceptionHandler;

use Closure;
use Phptg\BotApi\Type\Update\Update;
use Throwable;

/**
 * Describe method to manage and execute global exception handlers
 */
interface ExceptionHandlerInterface
{
    /**
     * @param  Closure  $callback  - Throwable type passes as Throwable $e, DI supported
     */
    public function wrap(Closure $callback): void;

    /**
     * Provoke exception handlers stack
     */
    public function catch(Update $update, Throwable $e): void;

    /**
     * Utilize exception handlers stack
     */
    public function reset(): void;
}
