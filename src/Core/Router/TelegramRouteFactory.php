<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Closure;
use Illuminate\Routing\Route;
use Lowel\Telepath\Enums\UpdateTypeEnum;

final class TelegramRouteFactory
{
    public function make(UpdateTypeEnum $updateType, string|callable|Closure|array $handler, ?string $pattern = null): Route
    {
        return (new Route(
            ['POST'],
            sprintf('telepath/%s/%s', $updateType->value, $pattern === null ? '{any?}' : '{payload}'),
            $handler
        ))->where($pattern === null ? 'any' : 'payload', $pattern ?? '.*');
    }
}
