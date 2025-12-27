<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram;

use Illuminate\Support\Str;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\FileMetadataInterface;

final readonly class TelegramReplyButtonFileMetadata implements FileMetadataInterface
{
    public function getPath(): string
    {
        return app_path('/Telegram/Keyboards/Reply');
    }

    public function getNamespace(): string
    {
        return 'App\\Telegram\\Keyboards\\Reply';
    }

    public function convertInClassName(string $argumentName): string
    {
        if (Str::endsWith($argumentName, 'ReplyButton')) {
            return $argumentName;
        } else {
            return "{$argumentName}ReplyButton";
        }
    }
}
