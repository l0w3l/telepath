<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Lowel\Telepath\Core\Router\Context\Executor\RouteExecutorInterface;

/**
 * @extends RouteContextInterface<RouteContextInterface>
 */
interface GroupContextInterface extends RouteContextInterface
{
    /**
     * @return RouteExecutorInterface[]
     */
    public function collect(): array;

    public function appendRouteContext(RouteContextInterface $routeContext): self;

    public function wrap(RouteContextParams $routeContextParams): GroupContextInterface;

    public function unwrap(): ?GroupContextInterface;
}
