<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Illuminate\Http\Request;
use Lowel\Telepath\Enums\UpdateTypeEnum;

final class TelegramRouteMatcher
{
    public function match(TelegramRouteRegistry $registry, UpdateTypeEnum $updateType, Request $request): ?TelegramRouteDefinition
    {
        foreach ($registry as $definition) {
            if ($definition->updateType() !== $updateType) {
                continue;
            }

            if ($definition->route()?->matches($request)) {
                return $definition;
            }
        }

        return null;
    }
}
