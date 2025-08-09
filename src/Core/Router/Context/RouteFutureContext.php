<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Closure;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Conversation\TelegramConversationInterface;
use Lowel\Telepath\Core\Router\Conversation\TelegramPromiseInterface;

/**
 * Class RouteFutureContext
 *
 * This class extends the RouteContext to provide additional functionality for handling promises and conversations.
 * It allows for asynchronous handling of routes by enabling the registration of promises that can be resolved later.
 */
readonly class RouteFutureContext extends RouteContext implements RouteFutureContextInterface
{
    public function __construct(RouteContextParams $params)
    {
        parent::__construct($params);
    }

    public static function from(RouteContextInterface $routeContext): RouteFutureContextInterface
    {
        if ($routeContext instanceof RouteContext) {
            return new self($routeContext->params);
        }

        return new self($routeContext->getParams());
    }

    public function promise(TelegramPromiseInterface|Closure $resolve, ?Closure $reject = null, ?int $ttl = null): RouteFutureContextInterface
    {
        if (($resolveObject = $resolve) instanceof TelegramPromiseInterface) {
            /** @phpstan-ignore-next-line  */
            $resolve = $resolveObject->resolve(...);

            /** @phpstan-ignore-next-line  */
            if (method_exists($resolveObject, 'reject')) {
                /** @phpstan-ignore-next-line  */
                $reject = $resolveObject->reject(...);
            }

            /** @phpstan-ignore-next-line  */
            if (method_exists($resolveObject, 'ttl')) {
                $ttl = $resolveObject->ttl();
            }
        }

        $this->params->appendConversation($resolve, $reject, $ttl);

        return $this;
    }

    public function conversation(TelegramConversationInterface|string $conversation): RouteFutureContextInterface
    {
        if (is_string($conversation)) {
            $conversation = App::make($conversation);

            if (! $conversation instanceof TelegramConversationInterface) {
                throw new \InvalidArgumentException(sprintf(
                    'Expected instance of %s, got %s',
                    TelegramConversationInterface::class,
                    get_debug_type($conversation)
                ));
            }
        }

        foreach ($conversation->promises() as $promise) {
            /** @phpstan-ignore-next-line  */
            $this->promise($promise->resolve(...), $promise->reject(...), $promise->ttl());
        }

        return $this;
    }
}
