<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Lowel\Telepath\Core\Router\Context\Executor\RouteRouteExecutor;
use Lowel\Telepath\Core\Router\Context\Executor\Traits\InvokeAbleTrait;
use Lowel\Telepath\Enums\UpdateTypeEnum;

/**
 * GroupContext is a context for grouping multiple route contexts together.
 */
class GroupContext implements GroupContextInterface
{
    use InvokeAbleTrait;

    /**
     * @var array<RouteContextInterface|GroupContextInterface>
     */
    public array $contexts = [];

    public function __construct(
        public ?GroupContextInterface $prev = null,
        public RouteContextParams $params = new RouteContextParams,
    ) {}

    public function appendRouteContext(RouteContextInterface|GroupContextInterface $routeContext): GroupContextInterface
    {
        $this->contexts[] = $routeContext;

        return $this;
    }

    public function wrap(RouteContextParams $routeContextParams): GroupContextInterface
    {
        return new self($this, $routeContextParams);
    }

    public function unwrap(): ?GroupContextInterface
    {
        return $this->prev;
    }

    public function type(UpdateTypeEnum $updateTypeEnum): self
    {
        $this->params->setUpdateTypeEnum($updateTypeEnum);

        return $this;
    }

    public function middleware(callable|array|string $handler): self
    {
        $this->params->pushMiddleware($handler);

        return $this;
    }

    public function name(string $name): self
    {
        $this->params->setName($name);

        return $this;
    }

    public function pattern(string $pattern): self
    {
        $this->params->setPattern($pattern);

        return $this;
    }

    public function getParams(): RouteContextParams
    {
        return $this->params;
    }

    public function collect(): array
    {
        $executors = [];

        foreach ($this->contexts as $context) {
            if ($context instanceof GroupContextInterface) {
                $executors = array_merge($executors, $context->collect());
            } else {
                $contextParams = $context->getParams();

                $executors[] = new RouteRouteExecutor($contextParams);
            }
        }

        foreach ($executors as $executor) {
            $executor->affect($this->params);
        }

        return $executors;
    }
}
