<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Closure;
use Lowel\Telepath\Core\Router\Conversation\TelegramConversationInterface;
use Lowel\Telepath\Core\Router\Conversation\TelegramPromiseInterface;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for route context that supports asynchronous handling.
 *
 * @extends RouteContextInterface<RouteFutureContextInterface>
 *
 * @phpstan-type ResolveClosure = Closure(TelegramBotApi $api, Update $update): void
 * @phpstan-type RejectClosure = Closure(TelegramBotApi $api, Update $update, Throwable $error): void
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
