<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;

interface TelegramHandlerCollectionInterface
{
    /**
     * @return TelegramHandlerInterface[]
     *
     * @throws TelegramHandlerNotFoundException
     */
    public function getHandlersBy(UpdateTypeEnum $typeEnum, ?string $data = null): array;

    /**
     * @return TelegramHandlerInterface[]
     */
    public function getFallbacks(): array;
}
