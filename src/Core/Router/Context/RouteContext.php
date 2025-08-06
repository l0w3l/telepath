<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Lowel\Telepath\Core\Router\Context\Executor\Traits\InvokeAbleTrait;
use Lowel\Telepath\Enums\UpdateTypeEnum;

/**
 * @implements RouteContextInterface<RouteContextInterface>
 */
final readonly class RouteContext implements RouteContextInterface
{
    use InvokeAbleTrait;

    public function __construct(
        private RouteContextParams $params,
    ) {}

    public function getParams(): RouteContextParams
    {
        return $this->params->clone();
    }

    public function middleware(callable|array|string $handler): RouteContextInterface
    {
        $this->params->pushMiddleware($handler);

        return $this;
    }

    public function name(string $name): RouteContextInterface
    {
        $this->params->setName($name);

        return $this;
    }

    public function pattern(string $pattern): RouteContextInterface
    {
        $this->params->setPattern($pattern);

        return $this;
    }

    public function type(UpdateTypeEnum $updateTypeEnum)
    {
        $this->params->setUpdateTypeEnum($updateTypeEnum);

        return $this;
    }
}
