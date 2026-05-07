<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context;

use Lowel\Telepath\Core\Router\Context\Executor\RouteExecutor;
use Lowel\Telepath\Core\Router\Context\Executor\RouteExecutorInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;

/**
 * GroupContext is a context for grouping multiple route contexts together.
 */
final class GroupContext implements GroupContextInterface
{
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
        $group = new self($this, $routeContextParams);
        $this->appendRouteContext($group);

        return $group;
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
        $this->collectInto($executors);

        return $executors;
    }

    /**
     * @param  array<RouteExecutorInterface>  $executors
     */
    private function collectInto(array &$executors): void
    {
        $startCount = count($executors);

        foreach ($this->contexts as $context) {
            if ($context instanceof self) {
                $context->collectInto($executors);
            } elseif ($context instanceof GroupContextInterface) {
                foreach ($context->collect() as $executor) {
                    $executors[] = $executor;
                }
            } else {
                $executors[] = new RouteExecutor($context->getParams());
            }
        }

        $endCount = count($executors);

        for ($i = $startCount; $i < $endCount; $i++) {
            $executors[$i]->affect($this->params);
        }
    }
}
