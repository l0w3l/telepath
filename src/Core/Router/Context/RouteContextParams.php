<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Closure;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Conversation\Promise\TelegramPromiseInterface;
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
     * @param  array<Closure(TelegramBotApi, Update, callable): void>  $middlewares
     * @param  array<TelegramPromiseInterface>  $conversation
     */
    public function __construct(
        private ?TelegramHandlerInterface $handler = null,
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

    public function setHandler(TelegramHandlerInterface $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    public function getHandler(): TelegramHandlerInterface
    {
        return $this->handler ?? throw new RuntimeException('Handler is not set.');
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
        if ($this->pattern !== null) {
            $this->pattern = $pattern.$this->pattern;
        } else {
            $this->pattern = $pattern;
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

    public function matchPattern(?string $text): bool
    {

        if ($this->hasPattern()) {
            $pattern = $this->getPattern();

            if (str_starts_with($pattern, '/') && str_ends_with($pattern, '/')) {
                return preg_match(sprintf('%s', $pattern), $text ?? '') === 1;
            } elseif (str_starts_with($pattern, '/')) {
                return preg_match(sprintf('/^\\%s$/', $pattern), $text ?? '') === 1;
            } else {
                return preg_match(sprintf('/^%s$/', $pattern), $text ?? '') === 1;
            }
        }

        return true;
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

    public function appendConversationPromise(TelegramPromiseInterface $promise): self
    {
        $this->conversation[] = $promise;

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

    public function getConversationPosition(int $index): TelegramPromiseInterface
    {
        return $this->conversation[$index] ?? throw new RuntimeException("Promise with index {$index} does not exist.");
    }

    public function hasConversation(): bool
    {
        return ! empty($this->conversation);
    }
}
