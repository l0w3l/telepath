<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation\Promise;

use Closure;
use Throwable;

class TelegramPromiseFactory
{
    public function fromCallable(callable $resolve, ?callable $reject = null, ?int $ttl = null): TelegramPromiseInterface
    {
        return new class($resolve, $reject, $ttl) extends AbstractTelegramPromise
        {
            public function __construct(private readonly Closure $resolveCallable, private readonly ?Closure $rejectCallable = null, private readonly ?int $ttlValue = null) {}

            public function resolve(): callable
            {
                return $this->resolveCallable;
            }

            public function reject(Throwable $error): ?callable
            {
                return $this->rejectCallable;
            }

            public function ttl(): int
            {
                return $this->ttlValue ?? parent::ttl();
            }
        };
    }
}
