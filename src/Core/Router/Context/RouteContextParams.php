<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Closure;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Conversation\TelegramPromiseInterface;
use Lowel\Telepath\Core\Router\Conversation\TelegramPromiseWrapper;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerInterface;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use RuntimeException;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Class RouteContextParams
 *
 * This class holds parameters for a route context, including the handler, update type,
 * middlewares, name, pattern, and promise.
 */
final class RouteContextParams
{
    /**
     * @param  TelegramHandlerInterface|Closure(TelegramBotApi, Update): void|null  $handler
     * @param  array<Closure(TelegramBotApi, Update, callable): void>  $middlewares
     * @param  array<TelegramPromiseWrapper>  $conversation
     */
    public function __construct(
        private null|TelegramHandlerInterface|Closure $handler = null,
        private ?UpdateTypeEnum $updateTypeEnum = null,
        private array $middlewares = [],
        private ?string $name = null,
        private ?string $pattern = null,
        private array $conversation = []
    ) {}

    public function clone(): self
    {
        return new self(
            $this->handler,
            $this->updateTypeEnum,
            $this->middlewares,
            $this->name,
            $this->pattern,
            $this->conversation
        );
    }

    public function isEmpty(): bool
    {
        return $this->handler === null;
    }

    public function setHandler(TelegramHandlerInterface|Closure $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    public function getHandler(): TelegramHandlerInterface|Closure|null
    {
        return $this->handler;
    }

    public function setUpdateTypeEnum(?UpdateTypeEnum $updateTypeEnum): self
    {
        if ($updateTypeEnum !== null) {
            $this->updateTypeEnum = $updateTypeEnum;
        }

        return $this;
    }

    public function getUpdateTypeEnum(): ?UpdateTypeEnum
    {
        return $this->updateTypeEnum;
    }

    public function hasUpdateTypeEnum(): bool
    {
        return $this->updateTypeEnum !== null;
    }

    public function pushMiddleware(callable|array|string $handler): self
    {
        if (is_array($handler)) {
            foreach ($handler as $handlerPart) {
                $this->pushMiddleware($handlerPart);
            }
        } elseif (is_string($handler)) {
            $class = App::make($handler);

            if (! is_object($class) || ! ($class instanceof TelegramMiddlewareInterface)) {
                throw new RuntimeException("Middleware {$handler} should implement TelegramMiddlewareInterface");
            }

            $this->middlewares[] = $class;
        } else {
            $this->middlewares[] = $handler;
        }

        return $this;
    }

    public function unshiftMiddleware(callable|array|string $middleware): self
    {
        $oldMiddlewares = $this->getMiddlewares();

        $this->resetMiddlewares();

        $middlewaresToAdd = is_array($middleware) ? $middleware : [$middleware];
        $this->pushMiddleware([...$middlewaresToAdd, ...$oldMiddlewares]);

        return $this;
    }

    public function getMiddlewaresReverse(): array
    {
        return array_reverse($this->getMiddlewares());
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function resetMiddlewares(): self
    {
        $this->middlewares = [];

        return $this;
    }

    public function setName(?string $name): self
    {
        if ($name !== null) {
            if ($this->name !== null) {
                $this->name = $name.$this->name;

            } else {
                $this->name = $name;
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setPattern(?string $pattern): self
    {
        if ($pattern !== null) {
            if ($this->pattern !== null) {
                $this->pattern = $pattern.$this->pattern;

            } else {
                $this->pattern = $pattern;
            }
        }

        return $this;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function hasPattern(): bool
    {
        return $this->pattern !== null;
    }

    public function reset(): self
    {
        $this->handler = null;
        $this->updateTypeEnum = null;
        $this->middlewares = [];
        $this->name = null;
        $this->pattern = null;
        $this->conversation = [];

        return $this;
    }

    public function appendConversation(Closure $then, ?Closure $catch, ?int $ttl): self
    {
        $this->conversation[] = new TelegramPromiseWrapper($then, $catch, $ttl);

        return $this;
    }

    public function getConversation(): array
    {
        return $this->conversation;
    }

    public function getConversationLength(): int
    {
        return count($this->conversation);
    }

    public function getConversationPart(int $index): TelegramPromiseInterface
    {
        return $this->conversation[$index] ?? throw new RuntimeException("Promise with index {$index} does not exist.");
    }

    public function hasConversation(): bool
    {
        return ! empty($this->conversation);
    }
}
