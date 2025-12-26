<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Files\Telegram;

use Illuminate\Support\Str;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\FileMetadataInterface;

final readonly class TelegramConversationFileMetadata implements FileMetadataInterface
{
    public function getPath(): string
    {
        return app_path('/Telegram/Conversations');
    }

    public function getNamespace(): string
    {
        return 'App\\Telegram\\Conversations';
    }

    public function convertInClassName(string $argumentName): string
    {
        if (Str::endsWith($argumentName, 'Conversation')) {
            return $argumentName;
        } else {
            return "{$argumentName}Conversation";
        }
    }
}
