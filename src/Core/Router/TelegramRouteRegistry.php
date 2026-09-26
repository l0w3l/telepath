<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Traversable;

/**
 * @implements IteratorAggregate<int, TelegramRouteDefinition>
 */
final class TelegramRouteRegistry implements Countable, IteratorAggregate
{
    /**
     * @var list<TelegramRouteDefinition>
     */
    private array $definitions = [];

    public function append(TelegramRouteDefinition $definition): TelegramRouteDefinition
    {
        $this->definitions[] = $definition;

        return $definition;
    }

    /**
     * @param  string|callable|Closure|array  $handler
     * @param  array<int|string, mixed>  $middleware
     */
    public function register(
        UpdateTypeEnum $updateType,
        string|callable|Closure|array $handler,
        ?string $pattern = null,
        array $middleware = [],
    ): TelegramRouteDefinition {
        return $this->append(new TelegramRouteDefinition($updateType, $handler, $pattern, $middleware));
    }

    /**
     * @return list<TelegramRouteDefinition>
     */
    public function all(): array
    {
        return $this->definitions;
    }

    public function count(): int
    {
        return count($this->definitions);
    }

    public function clear(): void
    {
        $this->definitions = [];
    }

    /**
     * @return Traversable<int, TelegramRouteDefinition>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->definitions);
    }
}
