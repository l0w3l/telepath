<?php

declare(strict_types=1);

namespace Lowel\Telepath;

use Lowel\Telepath\Core\TelegramAppInterface;
use Psr\Http\Message\ServerRequestInterface;

interface TelegramAppFactoryInterface
{
    public function longPooling(): TelegramAppInterface;

    public function webhook(ServerRequestInterface $request): TelegramAppInterface;
}
