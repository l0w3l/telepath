<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Traits;

use Lowel\Telepath\Core\Router\Conversation\Storage\ConversationStorage;
use Lowel\Telepath\Core\Router\Conversation\Storage\ConversationStorageFactory;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Exceptions\Router\ConversationException;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;
use RuntimeException;
use Throwable;

trait UpdateHandlerTrait
{
    public readonly TelegramBotApi $telegramBotApi;

    public readonly TelegramRouterResolverInterface $routerResolver;

    protected function handleUpdate(Update $update): void
    {
        $conversationStorage = (new ConversationStorageFactory)->create($update);

        if ($update->message && $conversationStorage->hasActiveConversation()) {
            $this->resolveConversation($conversationStorage, $update);
        } else {
            $this->resolveUpdate($update);
        }
    }

    private function resolveConversation(ConversationStorage $conversationStorage, Update $update): void
    {
        $conversationPositionData = $conversationStorage->getAvailableConversationPositionForCurrentContext();

        $executor = $this->routerResolver->getExecutors()
            ->resolveConversation($conversationPositionData);

        $promise = $executor->params()->getConversationPosition($conversationPositionData->position);

        $newTtl = match ($conversationPositionData->position + 1 < $conversationPositionData->end) {
            true => $executor->params()->getConversationPosition($conversationPositionData->position + 1)->ttl(),
            false => 0,
        };

        try {
            try {
                $shared = $promise->execResolve([
                    'api' => $this->telegramBotApi,
                    'update' => $update,
                    'shared' => $conversationPositionData->shared,
                ]);

                $conversationStorage->tickConversation($conversationPositionData, $newTtl, $shared);
            } catch (Throwable $error) {
                $promise->execReject($error, [
                    'api' => $this->telegramBotApi,
                    'update' => $update,
                    'shared' => $conversationPositionData->shared,
                ]);

                // reset state and alert about error
                if (false === ($error instanceof ConversationException)) {
                    $conversationStorage->delete();
                }
            }
        } catch (Throwable $error) {
            $conversationStorage->delete();

            if ($error instanceof RuntimeException) {
                throw $error;
            } else {
                throw new RuntimeException(previous: $error);
            }
        }
    }

    private function resolveUpdate(Update $update): void
    {
        $executors = $this->routerResolver->getExecutors();

        foreach ($executors->resolve($update) as $handler) {
            $handler->proceed($this->telegramBotApi, $update);
        }
    }
}
