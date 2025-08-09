<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Lowel\Telepath\Core\Router\Context\Executor\RouteExecutorInterface;

/**
 * Interface for grouping multiple route contexts together.
 * This interface extends the RouteContextInterface to provide additional functionality
 * for collecting and managing multiple route executors.
 *
 *
 * @extends RouteContextInterface<RouteContextInterface>
 */
interface GroupContextInterface extends RouteContextInterface
{
    /**
     * @return RouteExecutorInterface[]
     */
    public function collect(): array;

    public function appendRouteContext(RouteContextInterface $routeContext): self;

    public function wrap(RouteContextParams $routeContextParams): self;

    public function unwrap(): ?self;
}
