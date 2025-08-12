<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Conversation;

use Illuminate\Contracts\Cache\Repository;
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

    public function has(): bool
    {
        try {
            return $this->cache->has($this->resolveKey());
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @param  array<TelegramPromiseInterface>  $conversation
     *
     * @throws InvalidArgumentException
     */
    public function initialize(array $conversation): self
    {
        if (! empty($conversation)) {
            $this->cache->set(
                $this->resolveKey(),
                serialize($conversation),
                $conversation[0]->ttl()
            );
        } else {
            $this->cache->delete($this->resolveKey());
        }

        return $this;
    }

    /**
     * @return array<TelegramPromiseInterface>
     *
     * @throws InvalidArgumentException
     */
    public function get(): array
    {
        return unserialize($this->cache->get($this->resolveKey()));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(): void
    {
        $this->cache->delete($this->resolveKey());
        $this->cache->delete($this->resolveKey().'.shared');
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

    /**
     * @throws InvalidArgumentException
     */
    public function popPromise(): TelegramPromiseInterface
    {
        $conversation = $this->get();

        $promise = array_shift($conversation);

        $this->initialize($conversation);

        return $promise;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function pushPromise(TelegramPromiseInterface $promise): self
    {
        $conversation = $this->get();

        array_unshift($conversation, $promise);

        $this->initialize($conversation);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getShared(): mixed
    {
        return $this->cache->get($this->resolveKey().'.shared', '');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function storeShared($shared, TelegramPromiseInterface $promise): void
    {
        $this->cache->set($this->resolveKey().'.shared', $shared, $promise->ttl());
    }
}
