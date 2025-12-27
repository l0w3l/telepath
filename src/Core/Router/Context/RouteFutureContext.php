<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Closure;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Conversation\Promise\TelegramPromiseFactory;
use Lowel\Telepath\Core\Router\Conversation\Promise\TelegramPromiseInterface;
use Lowel\Telepath\Core\Router\Conversation\TelegramConversationInterface;

/**
 * Class RouteFutureContext
 *
 * This class extends the RouteContext to provide additional functionality for handling promises and conversations.
 * It allows for asynchronous handling of routes by enabling the registration of promises that can be resolved later.
 */
readonly class RouteFutureContext extends RouteContext implements RouteFutureContextInterface
{
    private TelegramPromiseFactory $telegramPromiseFactory;

    public function __construct(RouteContextParams $params)
    {
        parent::__construct($params);

        $this->telegramPromiseFactory = new TelegramPromiseFactory;
    }

    public static function from(RouteContextInterface $routeContext): RouteFutureContextInterface
    {
        if ($routeContext instanceof RouteContext) {
            return new self($routeContext->params);
        }

        return new self($routeContext->getParams());
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
            if (is_array($promise) && isset($promise['resolve'])) {
                $this->promise($promise['resolve'], $promise['reject'] ?? null, $promise['ttl'] ?? null);
            } else {
                $this->promise($promise);
            }
        }

        return $this;
    }

    public function promise(TelegramPromiseInterface|Closure $resolve, ?Closure $reject = null, ?int $ttl = null): RouteFutureContextInterface
    {
        if (! (($resolveObject = $resolve) instanceof TelegramPromiseInterface)) {
            $resolveObject = $this->telegramPromiseFactory->fromCallable($resolve, $reject, $ttl);
        }

        $this->params->appendConversationPromise($resolveObject);

        return $this;
    }
}
