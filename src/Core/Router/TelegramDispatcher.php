<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Router;
use Lowel\Telepath\Components\Context\Context;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Phptg\BotApi\Type\Update\Update;
use Symfony\Component\HttpFoundation\Response;

final readonly class TelegramDispatcher
{
    public function __construct(
        private Container $container,
        private Context $context,
        private TelegramRouteRegistry $registry,
        private TelegramRouteMatcher $matcher,
        private Router $router,
    ) {}

    public function dispatch(Update $update, ?UpdateTypeEnum $onlyType = null, ?string $payload = null, bool $manageContext = true): Response
    {
        $previousType = $manageContext ? null : $this->context->type();

        if ($manageContext) {
            $this->context->onBefore($update);
        }

        try {
            $response = response(status: 200);

            foreach ($onlyType === null ? UpdateTypeEnum::resolve($update) : [$onlyType] as $updateType) {
                $this->context->setType($updateType);

                $response = $this->dispatchType($update, $updateType, $payload);
            }

            return $response;
        } finally {
            if ($manageContext) {
                $this->context->onAfter($update);
            } elseif ($previousType !== null) {
                $this->context->setType($previousType);
            }
        }
    }

    private function dispatchType(Update $update, UpdateTypeEnum $updateType, ?string $payload = null): Response
    {
        $payload ??= $this->extractPayload($update, $updateType);
        $request = $payload === null
            ? RequestFactory::fromUpdate($updateType, $update)
            : RequestFactory::fromRaw($update, $updateType, $payload);
        $definition = $this->matcher->match($this->registry, $updateType, $request);

        if ($definition === null) {
            return response(status: 200);
        }

        $route = $definition->route();
        $route->setContainer($this->container)->setRouter($this->router);
        $route->bind($request);
        $request->setRouteResolver(static fn () => $route);

        $originalRequest = $this->container->make('request');
        $this->container->instance('request', $request);

        try {
            $result = $this->container->make(Pipeline::class)
                ->send($request)
                ->through($this->middlewareFor($definition))
                ->then(static fn (Request $request): mixed => $route->run());
        } finally {
            $this->container->instance('request', $originalRequest);
        }

        return $result instanceof Response ? $result : response(status: 200);
    }

    private function extractPayload(Update $update, UpdateTypeEnum $updateType): ?string
    {
        $payload = UpdateTypeEnum::extractText($update, $updateType);

        if ($payload !== null && str_starts_with($payload, '/')) {
            return substr($payload, 1);
        }

        return $payload;
    }

    /**
     * @return array<int|string, mixed>
     */
    private function middlewareFor(TelegramRouteDefinition $definition): array
    {
        $middleware = [
            ...$definition->middleware(),
            ...$definition->route()->gatherMiddleware(),
        ];

        return $this->router->resolveMiddleware($middleware, $definition->route()->excludedMiddleware());
    }
}
