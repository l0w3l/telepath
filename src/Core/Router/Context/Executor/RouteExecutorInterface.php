<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context\Executor;

use Lowel\Telepath\Core\Router\Context\RouteContextParams;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * Interface for route context executors.
 * Route context executors are responsible for executing the route context,
 * which includes matching the update type and executing the handler.
 */
interface RouteExecutorInterface
{
    public function affect(RouteContextParams $params): self;

    public function proceed(TelegramBotApi $api, Update $update): void;

    public function match(UpdateTypeEnum $updateTypeEnum, ?string $text = null): bool;

    public function params(): RouteContextParams;
}
