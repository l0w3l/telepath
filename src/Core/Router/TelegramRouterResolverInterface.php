<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Lowel\Telepath\Core\Router\Context\Executor\RouteExecutorsCollection;

interface TelegramRouterResolverInterface
{
    public function getExecutors(): RouteExecutorsCollection;

    public function resetState(): self;
}
