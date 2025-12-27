<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Middleware;

abstract class AbstractTelegramMiddleware implements TelegramMiddlewareInterface
{
    public function handler(): callable
    {
        if (method_exists($this, '__invoke')) {
            return $this(...);
        } else {
            throw new \RuntimeException('Method __invoke() in '.self::class.' does not exist. implement handler() or __invoke() methods to proceed updates.');
        }
    }
}
