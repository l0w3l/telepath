<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram;

use Illuminate\Support\Str;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\FileMetadataInterface;

final readonly class TelegramInlineKeyboardFileMetadata implements FileMetadataInterface
{
    public function getPath(): string
    {
        return app_path('/Telegram/Keyboards/Inline');
    }

    public function getNamespace(): string
    {
        return 'App\\Telegram\\Keyboards\\Inline';
    }

    public function convertInClassName(string $argumentName): string
    {
        if (Str::endsWith($argumentName, 'InlineKeyboardFactory')) {
            return $argumentName;
        } else {
            return "{$argumentName}InlineKeyboardFactory";
        }
    }
}
