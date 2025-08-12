<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Lowel\Telepath\Core\Router\Context\Executor\RouteExecutorInterface;

interface TelegramRouterResolverInterface
{
    /**
     * @return RouteExecutorInterface[]
     */
    public function getFallbacks(): array;

    /**
     * @return RouteExecutorInterface[]
     */
    public function getHandlers(): array;

    public function resetState(): self;
}
