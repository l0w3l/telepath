<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Illuminate\Support\Facades\App;
use Lowel\Telepath\Commands\Exceptions\Router\SubPathNotFoundException;
use Lowel\Telepath\Core\Router\Handler\TelegramHandler;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerCollectionInterface;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerInterface;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;
use RuntimeException;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

class TelegramRouter implements TelegramHandlerCollectionInterface, TelegramRouterInterface
{
    /**
     * @var array<value-of<UpdateTypeEnum>, RoutePathInterface>
     */
    protected array $handlers = [];

    protected int $groupStack = 0;

    /**
     * @var TelegramMiddlewareInterface[]
     */
    protected array $middlewareStack = [];

    /**
     * @var TelegramMiddlewareInterface[]
     */
    protected array $groupMiddlewareStack = [];

    /**
     * @var array<callable(TelegramBotApi, Update): mixed>
     */
    protected array $fallbacks = [];

    public function onMessage(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::MESSAGE);
    }

    public function onMessageEdit(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::EDITED_MESSAGE);
    }

    public function onChannelPost(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::EDITED_CHANNEL_POST);
    }

    public function onMessageReaction(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::MESSAGE_REACTION);
    }

    public function onMessageReactionCount(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::MESSAGE_REACTION_COUNT);
    }

    public function onChannelPostEdit(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::CHANNEL_POST);
    }

    public function onBusinessConnection(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::BUSINESS_CONNECTION);
    }

    public function onBusinessMessage(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::BUSINESS_MESSAGE);
    }

    public function onBusinessMessageEdit(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::EDIT_BUSINESS_MESSAGE);
    }

    public function onBusinessMessagesDelete(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::DELETE_BUSINESS_MESSAGES);
    }

    public function onInlineQueryChosenResult(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::CHOSEN_INLINE_RESULT);
    }

    public function onShippingQuery(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::SHIPPING_QUERY);
    }

    public function onPreCheckoutQuery(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::PRE_CHECKOUT_QUERY);
    }

    public function onPurchasedPaidMedia(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::PURCHASED_PAID_MEDIA);
    }

    public function onPoll(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::POLL);
    }

    public function onPollAnswer(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::POLL_ANSWER);
    }

    public function onChatJoinRequest(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::CHAT_JOIN_REQUEST);
    }

    public function onChatMemberUpdate(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::CHAT_MEMBER);
    }

    public function onChatBoost(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::CHAT_BOOST);
    }

    public function onChatBoostRemove(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::REMOVED_CHAT_BOOST);
    }

    public function onMyChatMemberUpdate(callable $handler): void
    {
        $this->on(null, $handler, UpdateTypeEnum::MY_CHAT_MEMBER);
    }

    public function onCallbackQuery(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::CALLBACK_QUERY);
    }

    public function onInlineQuery(callable $handler, ?string $pattern = null): void
    {
        $this->on($pattern, $handler, UpdateTypeEnum::INLINE_QUERY);
    }

    public function on(?string $pattern, callable $handler, UpdateTypeEnum $type = UpdateTypeEnum::MESSAGE): void
    {
        $handler = $this->bindMiddlewares($handler);

        $telegramHandler = new TelegramHandler($handler, $pattern);

        $this->setHandler($telegramHandler, $type);
    }

    public function group(callable $callback): void
    {
        $this->groupStack++;
        $oldGroupMiddlewareStack = $this->groupMiddlewareStack;
        $this->groupMiddlewareStack = array_merge($this->groupMiddlewareStack, $this->middlewareStack);
        $this->middlewareStack = [];

        $callback();

        $this->groupMiddlewareStack = $oldGroupMiddlewareStack;
        $this->groupStack--;
    }

    public function fallback(callable $handler): void
    {
        $this->fallbacks[] = new TelegramHandler($handler);
    }

    /**
     * @param  callable|class-string<TelegramMiddlewareInterface>|array<class-string<TelegramMiddlewareInterface>|callable>  $handler
     * @return $this
     */
    public function middleware(callable|string|array $handler): self
    {
        if (is_string($handler)) {
            try {
                $class = App::make($handler);

                if (is_callable($class)) {
                    $this->middlewareStack[] = $class;
                } else {
                    throw new RuntimeException("Class {$handler} should implement __invoke method or TelegramMiddlewareInterface");
                }
            } catch (Throwable $e) {
                throw new RuntimeException("Middleware was not bind by reason: {$e->getMessage()}", previous: $e);
            }
        } elseif (is_array($handler)) {
            foreach ($handler as $handlerPart) {
                $this->middleware($handlerPart);
            }
        } if (is_callable($handler)) {
            $this->middlewareStack[] = $handler;
        }

        return $this;
    }

    public function getFallbacks(): array
    {
        return $this->fallbacks;
    }

    public function getHandlersBy(UpdateTypeEnum $typeEnum, ?string $data = null): array
    {
        $routePath = $this->handlers[$typeEnum->value] ?? null;

        if ($routePath === null) {
            throw new TelegramHandlerNotFoundException("No handlers found for update type: {$typeEnum->value}");
        }

        return $routePath->matchAll($data);
    }

    protected function setHandler(TelegramHandlerInterface $handler, UpdateTypeEnum $updateTypeEnum): void
    {
        $this->handlers[$updateTypeEnum->value] ??= new RoutePath;

        $this->setHandlerRecursive($handler, $this->handlers[$updateTypeEnum->value], $this->groupStack);
    }

    private function setHandlerRecursive(TelegramHandlerInterface $handler, RoutePathInterface $routePath, int $depth): void
    {
        if ($depth !== 0) {

            try {
                $lastSubPath = $routePath->lastSubPath();
            } catch (SubPathNotFoundException $e) {
                $lastSubPath = new RoutePath;

                $routePath->appendRouePath($lastSubPath);
            }

            $this->setHandlerRecursive($handler, $lastSubPath, $depth - 1);
        } else {
            $routePath->appendHandler($handler);
        }
    }

    private function bindMiddlewares(callable $callable): callable
    {
        foreach (array_reverse($this->middlewareStack) as $middleware) {
            $callable = fn (TelegramBotApi $telegramBotApi, Update $update) => $middleware($telegramBotApi, $update, fn () => $callable($telegramBotApi, $update));
        }

        foreach (array_reverse($this->groupMiddlewareStack) as $middleware) {
            $callable = fn (TelegramBotApi $telegramBotApi, Update $update) => $middleware($telegramBotApi, $update, fn () => $callable($telegramBotApi, $update));
        }

        $this->middlewareStack = [];

        return $callable;
    }
}
