<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Closure;
use Lowel\Telepath\Core\Router\Conversation\Promise\TelegramPromiseInterface;
use Lowel\Telepath\Core\Router\Conversation\TelegramConversationInterface;

/**
 * Interface for route context that supports asynchronous handling.
 *
 * @extends RouteContextInterface<RouteFutureContextInterface>
 *
 * @phpstan-type ResolveClosure = Closure
 * @phpstan-type RejectClosure = Closure
 */
interface RouteFutureContextInterface extends RouteContextInterface
{
    /**
     * Creates a promise for the route context.
     *
     * @param  TelegramPromiseInterface|ResolveClosure  $resolve  The promise resolver, which can be a closure or an instance of TelegramPromiseInterface.
     * @param  RejectClosure|null  $reject  The promise rejector, which is optional and can be a closure.
     * @param  int|null  $ttl  The time-to-live for the promise, which is optional.
     */
    public function promise(TelegramPromiseInterface|Closure $resolve, ?Closure $reject = null, ?int $ttl = null): self;

    /**
     * Registers a conversation for the route context.
     *
     * @param  TelegramConversationInterface|class-string<TelegramConversationInterface>  $conversation  - The conversation to register, which can be an instance or a class name.
     */
    public function conversation(TelegramConversationInterface|string $conversation): self;
}
