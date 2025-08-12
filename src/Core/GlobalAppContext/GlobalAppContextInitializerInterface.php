<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\GlobalAppContext;

use Lowel\Telepath\Core\Drivers\TelegramAppDriverInterface;
use Vjik\TelegramBot\Api\Type\Update\Update;

interface GlobalAppContextInitializerInterface extends GlobalAppContextInterface
{
    public function setUpdate(Update $update): self;

    public function setDriver(TelegramAppDriverInterface $driver): self;

    public function destroy(): void;
}
