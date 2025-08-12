<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Context\GroupContext;
use Lowel\Telepath\Core\Router\Context\GroupContextInterface;
use Lowel\Telepath\Core\Router\Context\RouteContext;
use Lowel\Telepath\Core\Router\Context\RouteContextInterface;
use Lowel\Telepath\Core\Router\Context\RouteContextParams;
use Lowel\Telepath\Core\Router\Context\RouteFutureContext;
use Lowel\Telepath\Core\Router\Context\RouteFutureContextInterface;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerInterface;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use RuntimeException;

class TelegramRouter implements TelegramRouterInterface, TelegramRouterResolverInterface
{
    protected GroupContextInterface $mainGroupContext;

    protected GroupContextInterface $fallbackGroupContext;

    protected RouteContextParams $state;

    public function __construct()
    {
        $this->mainGroupContext = new GroupContext;
        $this->fallbackGroupContext = new GroupContext;
        $this->state = new RouteContextParams;
    }

    public function onMessage(string|callable $handler, ?string $pattern = null): RouteFutureContextInterface
    {
        return RouteFutureContext::from(
            $this->on($handler, UpdateTypeEnum::MESSAGE, $pattern)
        );
    }

    public function onMessageEdit(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::EDITED_MESSAGE, $pattern);
    }

    public function onChannelPost(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::EDITED_CHANNEL_POST, $pattern);
    }

    public function onMessageReaction(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::MESSAGE_REACTION);
    }

    public function onMessageReactionCount(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::MESSAGE_REACTION_COUNT);
    }

    public function onChannelPostEdit(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::CHANNEL_POST, $pattern);
    }

    public function onBusinessConnection(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::BUSINESS_CONNECTION);
    }

    public function onBusinessMessage(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::BUSINESS_MESSAGE, $pattern);
    }

    public function onBusinessMessageEdit(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::EDIT_BUSINESS_MESSAGE, $pattern);
    }

    public function onBusinessMessagesDelete(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::DELETE_BUSINESS_MESSAGES);
    }

    public function onInlineQueryChosenResult(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::CHOSEN_INLINE_RESULT, $pattern);
    }

    public function onShippingQuery(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::SHIPPING_QUERY);
    }

    public function onPreCheckoutQuery(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::PRE_CHECKOUT_QUERY);
    }

    public function onPurchasedPaidMedia(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::PURCHASED_PAID_MEDIA);
    }

    public function onPoll(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::POLL);
    }

    public function onPollAnswer(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::POLL_ANSWER);
    }

    public function onChatJoinRequest(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::CHAT_JOIN_REQUEST);
    }

    public function onChatMemberUpdate(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::CHAT_MEMBER);
    }

    public function onChatBoost(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::CHAT_BOOST);
    }

    public function onChatBoostRemove(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::REMOVED_CHAT_BOOST);
    }

    public function onMyChatMemberUpdate(string|callable $handler): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::MY_CHAT_MEMBER);
    }

    public function onCallbackQuery(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::CALLBACK_QUERY, $pattern);
    }

    public function onInlineQuery(string|callable $handler, ?string $pattern = null): RouteContextInterface
    {
        return $this->on($handler, UpdateTypeEnum::INLINE_QUERY, $pattern);
    }

    public function on(string|callable $handler, UpdateTypeEnum $type = UpdateTypeEnum::MESSAGE, ?string $pattern = null): RouteContextInterface
    {
        if (is_string($handler)) {
            $handler = App::make($handler);

            if (! is_object($handler) || ! ($handler instanceof TelegramHandlerInterface)) {
                throw new RuntimeException("Handler {$handler} should implement TelegramHandlerInterface");
            }
        }

        $context = new RouteContext(
            $this->state->clone()
                ->setHandler($handler)
                ->pushMiddleware(method_exists($handler, 'middlewares') ? $handler->middlewares() : [])
                ->setUpdateTypeEnum(method_exists($handler, 'type') ? $handler->type() : $type)
                ->setPattern(method_exists($handler, 'pattern') ? $handler->pattern() : $pattern)
        );

        $this->mainGroupContext->appendRouteContext($context);

        $this->resetState();

        return $context;
    }

    public function fallback(string|callable $handler): void
    {
        if (is_string($handler)) {
            $handler = App::make($handler);

            if (! is_object($handler) || ! ($handler instanceof TelegramHandlerInterface)) {
                throw new RuntimeException("Handler {$handler} should implement TelegramHandlerInterface");
            }
        }

        $this->fallbackGroupContext->appendRouteContext(
            new RouteContext(
                $this->state->clone()
                    ->setHandler($handler)
                    ->setUpdateTypeEnum(null)
                    ->setPattern(null)
            )
        );
    }

    public function group(callable $callback): RouteContextInterface
    {
        $childGroupContext = $this->mainGroupContext->wrap($this->state->clone());

        $this->state->reset();

        $this->mainGroupContext = $childGroupContext;

        $callback();

        $this->mainGroupContext = $this->mainGroupContext->unwrap();

        $this->mainGroupContext->appendRouteContext($childGroupContext);

        return $childGroupContext;
    }

    public function type(UpdateTypeEnum $updateTypeEnum): TelegramRouterInterface
    {
        $this->state->setUpdateTypeEnum($updateTypeEnum);

        return $this;
    }

    /**
     * @param  callable|class-string<TelegramMiddlewareInterface>|array<class-string<TelegramMiddlewareInterface>|callable>  $handler
     * @return $this
     */
    public function middleware(callable|string|array $handler): TelegramRouterInterface
    {
        $this->state->pushMiddleware($handler);

        return $this;
    }

    public function name(string $name): TelegramRouterInterface
    {
        $this->state->setName($name);

        return $this;
    }

    public function pattern(string $pattern): TelegramRouterInterface
    {
        $this->state->setPattern($pattern);

        return $this;
    }

    public function getParams(): RouteContextParams
    {
        return $this->state;
    }

    public function resetState(): self
    {
        $this->state->reset();

        return $this;
    }

    public function getHandlers(): array
    {
        return $this->mainGroupContext->collect();
    }

    public function getFallbacks(): array
    {
        return $this->fallbackGroupContext->collect();
    }
}
