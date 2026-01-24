<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation\Promise;

use Lowel\Telepath\Helpers\Invoker;
use RuntimeException;
use Throwable;

abstract class AbstractTelegramPromise implements TelegramPromiseInterface
{
    public function execResolve(array $params = []): mixed
    {
        return Invoker::call(
            $this->resolve(),
            $params,
        );
    }

    public function execReject(Throwable $throwable, array $params = []): mixed
    {
        $reject = $this->reject($throwable);

        if ($reject === null) {
            throw new RuntimeException(
                'Promise rejected without a reject handler. '.
                'Please provide a reject handler or handle the exception in your code.',
            );
        }

        return Invoker::call(
            $reject,
            $params
        );
    }

    public function ttl(): int
    {
        return config('telepath.conversation.ttl', 60 * 10); // Default TTL is 10 minutes
    }
}
