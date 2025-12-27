<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation\Promise;

use Throwable;

interface TelegramPromiseInterface
{
    /**
     * @param  array  $params  - Additional parameters for the resolve callback (passed directly through DI).
     * @return mixed - result of the resolve callback execution that would be stored in $shared var.
     */
    public function execResolve(array $params = []): mixed;

    /**
     * @param  Throwable  $throwable  - The error to prepare the reject callback with.
     * @param  array  $params  - Additional parameters for the reject callback (passed directly through DI).
     * @return mixed - result of the reject callback execution that would be stored in $shared var.
     */
    public function execReject(Throwable $throwable, array $params = []): mixed;

    /**
     * @return callable - DI supported methods. Resolves the conversation with the given API, update, and shared data
     */
    public function resolve(): callable;

    /**
     * @return ?callable - DI supported method. Rejects the conversation with the given API, update, error, and shared data.
     */
    public function reject(Throwable $error): ?callable;

    /**
     * @return int - the time-to-live for the conversation.
     */
    public function ttl(): int;
}
