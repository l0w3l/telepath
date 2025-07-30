<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Commands\Exceptions\Router\SubPathNotFoundException;
use Lowel\Telepath\Core\Router\Handler\TelegramHandler;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerCollectionInterface;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerInterface;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;
use RuntimeException;
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

    public function onMessage(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::MESSAGE, $pattern);
    }

    public function onMessageEdit(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::EDITED_MESSAGE, $pattern);
    }

    public function onChannelPost(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::EDITED_CHANNEL_POST, $pattern);
    }

    public function onMessageReaction(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::MESSAGE_REACTION);
    }

    public function onMessageReactionCount(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::MESSAGE_REACTION_COUNT);
    }

    public function onChannelPostEdit(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::CHANNEL_POST, $pattern);
    }

    public function onBusinessConnection(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::BUSINESS_CONNECTION);
    }

    public function onBusinessMessage(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::BUSINESS_MESSAGE, $pattern);
    }

    public function onBusinessMessageEdit(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::EDIT_BUSINESS_MESSAGE, $pattern);
    }

    public function onBusinessMessagesDelete(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::DELETE_BUSINESS_MESSAGES);
    }

    public function onInlineQueryChosenResult(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::CHOSEN_INLINE_RESULT, $pattern);
    }

    public function onShippingQuery(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::SHIPPING_QUERY);
    }

    public function onPreCheckoutQuery(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::PRE_CHECKOUT_QUERY);
    }

    public function onPurchasedPaidMedia(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::PURCHASED_PAID_MEDIA);
    }

    public function onPoll(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::POLL);
    }

    public function onPollAnswer(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::POLL_ANSWER);
    }

    public function onChatJoinRequest(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::CHAT_JOIN_REQUEST);
    }

    public function onChatMemberUpdate(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::CHAT_MEMBER);
    }

    public function onChatBoost(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::CHAT_BOOST);
    }

    public function onChatBoostRemove(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::REMOVED_CHAT_BOOST);
    }

    public function onMyChatMemberUpdate(string|callable $handler): void
    {
        $this->on($handler, UpdateTypeEnum::MY_CHAT_MEMBER);
    }

    public function onCallbackQuery(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::CALLBACK_QUERY, $pattern);
    }

    public function onInlineQuery(string|callable $handler, ?string $pattern = null): void
    {
        $this->on($handler, UpdateTypeEnum::INLINE_QUERY, $pattern);
    }

    public function on(string|callable $handler, UpdateTypeEnum $type = UpdateTypeEnum::MESSAGE, ?string $pattern = null): void
    {
        if (is_string($handler)) {
            $telegramHandler = App::make($handler);

            if (! is_object($telegramHandler) || ! ($telegramHandler instanceof TelegramHandlerInterface)) {
                throw new RuntimeException("Handler {$handler} should implement TelegramHandlerInterface");
            }
        } else {
            $telegramHandler = new TelegramHandler($handler, $pattern);
        }

        $wrappedHandler = $this->bindMiddlewares($telegramHandler);

        $this->setHandler($wrappedHandler, $type);
    }

    /**
     * @param  callable|class-string<TelegramMiddlewareInterface>|array<class-string<TelegramMiddlewareInterface>|callable>  $handler
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function middleware(callable|string|array $handler): self
    {
        if (is_array($handler)) {
            foreach ($handler as $handlerPart) {
                $this->middleware($handlerPart);
            }
        } elseif (is_string($handler)) {
            $class = App::make($handler);

            if (! is_object($class) || ! ($class instanceof TelegramMiddlewareInterface)) {
                throw new RuntimeException("Middleware {$handler} should implement TelegramMiddlewareInterface");
            }

            $this->middlewareStack[] = $class;
        } else {
            $this->middlewareStack[] = $handler;
        }

        return $this;
    }

    public function fallback(string|callable $handler): void
    {
        if (is_string($handler)) {
            $telegramHandler = App::make($handler);

            if (! is_object($telegramHandler) || ! ($telegramHandler instanceof TelegramHandlerInterface)) {
                throw new RuntimeException("Handler {$handler} should implement TelegramHandlerInterface");
            }
        } else {
            $telegramHandler = new TelegramHandler($handler);
        }

        $this->fallbacks[] = $telegramHandler;
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

    private function bindMiddlewares(TelegramHandlerInterface $telegramHandler): callable
    {
        foreach (array_reverse($this->middlewareStack) as $middleware) {
            $telegramHandler = new TelegramHandler(
                fn (TelegramBotApi $telegramBotApi, Update $update) => $middleware($telegramBotApi, $update, fn () => $telegramHandler($telegramBotApi, $update)),
                method_exists($telegramHandler, 'pattern') ? $telegramHandler->pattern() : null
            );
        }

        foreach (array_reverse($this->groupMiddlewareStack) as $middleware) {
            $telegramHandler = new TelegramHandler(
                fn (TelegramBotApi $telegramBotApi, Update $update) => $middleware($telegramBotApi, $update, fn () => $telegramHandler($telegramBotApi, $update)),
                method_exists($telegramHandler, 'pattern') ? $telegramHandler->pattern() : null
            );
        }

        $this->middlewareStack = [];

        return $telegramHandler;
    }
}
