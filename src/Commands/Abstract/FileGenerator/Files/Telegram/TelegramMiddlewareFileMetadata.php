<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram;

use Illuminate\Support\Str;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\FileMetadataInterface;

final readonly class TelegramMiddlewareFileMetadata implements FileMetadataInterface
{
    public function getPath(): string
    {
        return app_path('/Telegram/Middlewares');
    }

    public function getNamespace(): string
    {
        return 'App\\Telegram\\Middlewares';
    }

    public function convertInClassName(string $argumentName): string
    {
        if (Str::endsWith($argumentName, 'Middleware')) {
            return $argumentName;
        } else {
            return "{$argumentName}Middleware";
        }
    }
}
