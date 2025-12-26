<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation\Storage;

use Illuminate\Support\Facades\Cache;
use Phptg\BotApi\Type\Update\Update;

/**
 * Factory for creating ConversationStorage instances.
 *
 * This factory is responsible for creating a ConversationStorage instance
 * that uses the file cache store and is initialized with the provided Update.
 */
final class ConversationStorageFactory
{
    public function create(Update $update): ConversationStorage
    {
        return new ConversationStorage(Cache::store('file'), $update);
    }
}
