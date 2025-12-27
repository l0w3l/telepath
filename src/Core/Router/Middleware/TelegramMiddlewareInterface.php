<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Middleware;

/**
 * Interface for Telegram middleware.
 *
 * Middleware can be used to process updates before they reach the handler.
 * It can modify the update, perform logging, or handle specific conditions.
 */
interface TelegramMiddlewareInterface
{
    /**
     * Middleware handler creation
     *
     * @return callable - DI supported callable handler
     */
    public function handler(): callable;
}
