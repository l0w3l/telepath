<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Traits;

use Lowel\Telepath\Core\Router\Conversation\Storage\ConversationStorage;
use Lowel\Telepath\Core\Router\Conversation\Storage\ConversationStorageFactory;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Exceptions\Router\ConversationException;
use RuntimeException;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

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

        $promise = $executor->continueConversation($conversationPositionData);
        $newTtl = $executor->nextConversationTtl($conversationPositionData);

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

                // reset state
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
