<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation\Storage;

use Illuminate\Contracts\Cache\Repository;
use Lowel\Telepath\Core\Router\Conversation\Promise\TelegramPromiseInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Vjik\TelegramBot\Api\Type\Update\Update;

use function Opis\Closure\serialize;
use function Opis\Closure\unserialize;

/**
 * Class ConversationStorage
 *
 * This class manages the storage of Telegram conversations using a cache repository.
 * It allows for initializing, retrieving, deleting, and managing conversation promises.
 */
final readonly class ConversationStorage
{
    public function __construct(
        private Repository $cache,
        private Update $update,
    ) {}

    /**
     * @param  array<TelegramPromiseInterface>  $conversation
     */
    public function initialize(array $conversation, mixed $shared = null): self
    {
        try {
            $this->cache->set(
                $this->resolveKey(),
                serialize([
                    'trigger' => $this->update,
                    'position' => 0,
                    'end' => count($conversation),
                    'shared' => $shared,
                ]),
                $conversation[0]->ttl()
            );
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException('Unable to initialize conversation.', previous: $e);
        }

        return $this;
    }

    public function getAvailableConversationPositionForCurrentContext(): ConversationPositionData
    {
        try {
            return ConversationPositionData::fromArray(
                unserialize($this->cache->get($this->resolveKey()))
            );
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException('Unable to unserialize conversation status.', previous: $e);
        }
    }

    public function tickConversation(ConversationPositionData $conversationPositionDataOld, int $newTtl, mixed $shared): self
    {
        $newPosition = $conversationPositionDataOld->position + 1;

        if ($newPosition >= $conversationPositionDataOld->end) {
            $this->delete();

            return $this;
        }

        try {
            $this->cache->set($this->resolveKey(), serialize([
                'trigger' => $conversationPositionDataOld->trigger,
                'position' => $newPosition,
                'end' => $conversationPositionDataOld->end,
                'shared' => $shared,
            ]), $newTtl);
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException('Unable to tick conversation.', previous: $e);
        }

        return $this;
    }

    public function delete(): void
    {
        try {
            $this->cache->delete($this->resolveKey());
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException('Unable to delete message.', previous: $e);
        }
    }

    private function resolveKey(): string
    {
        $message = $this->update->message ?? throw new \RuntimeException('Unable to resolve chat or user ID from update.');

        return "telepath.promises.{$message->chat->id}.{$message->from->id}";
    }

    public function hasActiveConversation(): bool
    {
        try {
            return $this->cache->has($this->resolveKey());
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
