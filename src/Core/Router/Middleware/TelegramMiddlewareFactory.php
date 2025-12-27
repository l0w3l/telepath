<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use RuntimeException;

class TelegramMiddlewareFactory
{
    public function fromClassString(string $classString): TelegramMiddlewareInterface
    {
        try {
            $middleware = App::make($classString);

            if (! is_object($middleware) || ! ($middleware instanceof TelegramMiddlewareInterface)) {
                throw new RuntimeException("Middleware {$middleware} should implement TelegramMiddlewareInterface");
            }
        } catch (BindingResolutionException $e) {
            throw new RuntimeException('Cannot create middleware instance', previous: $e);
        }

        return $middleware;
    }

    public function fromCallable(callable $callable): TelegramMiddlewareInterface
    {
        return new class($callable) extends AbstractTelegramMiddleware
        {
            public function __construct(
                private readonly Closure $callable
            ) {}

            public function handler(): callable
            {
                return $this->callable;
            }
        };
    }
}
