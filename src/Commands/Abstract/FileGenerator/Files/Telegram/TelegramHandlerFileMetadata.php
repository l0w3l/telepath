<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram;

use Illuminate\Support\Str;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\FileMetadataInterface;

final readonly class TelegramHandlerFileMetadata implements FileMetadataInterface
{
    public function getPath(): string
    {
        return app_path('/Telegram/Handlers');
    }

    public function getNamespace(): string
    {
        return 'App\\Telegram\\Handlers';
    }

    public function convertInClassName(string $argumentName): string
    {
        if (Str::endsWith($argumentName, 'Handler')) {
            return $argumentName;
        } else {
            return "{$argumentName}Handler";
        }
    }
}
