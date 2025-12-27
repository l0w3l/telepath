<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Handler;

use Lowel\Telepath\Enums\UpdateTypeEnum;

abstract class AbstractTelegramHandler implements TelegramHandlerInterface
{
    public function handler(): callable
    {
        if (method_exists($this, '__invoke')) {
            return $this(...);
        } else {
            throw new \RuntimeException('Method __invoke() in '.self::class.' does not exist. implement handler() or __invoke() methods to proceed updates.');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function pattern(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function type(): ?UpdateTypeEnum
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function middlewares(): array
    {
        return [];
    }
}
