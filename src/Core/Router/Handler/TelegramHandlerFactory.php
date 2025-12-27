<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use RuntimeException;

class TelegramHandlerFactory
{
    public function fromClassString(string $classString): TelegramHandlerInterface
    {
        try {
            $handler = App::make($classString);

            if (! is_object($handler) || ! ($handler instanceof TelegramHandlerInterface)) {
                throw new RuntimeException("Handler {$handler} should implement TelegramHandlerInterface");
            }
        } catch (BindingResolutionException $e) {
            throw new RuntimeException('Cannot create handler instance', previous: $e);
        }

        return $handler;
    }

    public function fromCallable(callable $callable): TelegramHandlerInterface
    {
        return new class($callable) extends AbstractTelegramHandler
        {
            public function __construct(
                private Closure $callable
            ) {}

            public function handler(): callable
            {
                return $this->callable;
            }
        };
    }
}
