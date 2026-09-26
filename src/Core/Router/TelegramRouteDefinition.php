<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Closure;
use Illuminate\Routing\Route;
use Lowel\Telepath\Enums\UpdateTypeEnum;

final class TelegramRouteDefinition
{
    /**
     * PHP does not allow callable property types, so the constructor and accessor
     * keep the public contract explicit while this property stores the value.
     *
     * @var string|callable|Closure|array
     */
    private readonly mixed $handler;

    /**
     * @param  string|callable|Closure|array  $handler
     */
    public function __construct(
        private readonly UpdateTypeEnum $updateType,
        string|callable|Closure|array $handler,
        private readonly ?string $pattern = null,
        private array $middleware = [],
        private ?Route $route = null,
    ) {
        $this->handler = $handler;
    }

    public function updateType(): UpdateTypeEnum
    {
        return $this->updateType;
    }

    /**
     * @return string|callable|Closure|array
     */
    public function handler(): string|callable|Closure|array
    {
        return $this->handler;
    }

    public function pattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function middleware(): array
    {
        return $this->middleware;
    }

    /**
     * @param  array<int|string, mixed>  $middleware
     */
    public function appendMiddleware(array $middleware): self
    {
        $this->middleware = [...$this->middleware, ...$middleware];

        return $this;
    }

    public function route(): ?Route
    {
        return $this->route;
    }

    public function setRoute(Route $route): self
    {
        $this->route = $route;

        return $this;
    }
}
