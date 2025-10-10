<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Traits;

use Lowel\Telepath\Core\Router\Conversation\ConversationStorage;
use Lowel\Telepath\Core\Router\Conversation\ConversationStorageFactory;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;
use RuntimeException;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

trait UpdateHandlerTrait
{
    public readonly TelegramBotApi $telegramBotApi;

    public readonly TelegramRouterResolverInterface $routerResolver;

    /**
     * @throws TelegramHandlerNotFoundException
     */
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
        $promise = $conversationStorage->popPromise();
        $shared = $conversationStorage->getShared();

        try {
            try {
                /** @phpstan-ignore-next-line  */
                $shared = $promise->resolve($this->telegramBotApi, $update, $shared);

                $conversationStorage->storeShared($shared, $promise);
            } catch (Throwable $error) {
                /** @phpstan-ignore-next-line  */
                $promise->reject($this->telegramBotApi, $update, $error, $shared);

                // reset state
                $conversationStorage->pushPromise($promise);
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

    /**
     * @throws TelegramHandlerNotFoundException
     */
    private function resolveUpdate(Update $update): void
    {
        $types = UpdateTypeEnum::resolve($update);
        $executors = [];

        foreach ($types as $type) {
            $text = $this->extractText($update, $type);

            foreach ($this->routerResolver->getHandlers() as $executor) {
                if ($executor->match($type, $text)) {
                    $executors[] = $executor;
                }
            }
        }

        if (empty($executors)) {
            $executors = $this->routerResolver->getFallbacks();
        }

        foreach ($executors as $handler) {
            $handler->proceed($this->telegramBotApi, $update);
        }
    }

    private function extractText(Update $update, UpdateTypeEnum $type): ?string
    {
        return match ($type) {
            UpdateTypeEnum::MESSAGE => $update->message->text,
            UpdateTypeEnum::EDITED_MESSAGE => $update->editedMessage->text,
            UpdateTypeEnum::CHANNEL_POST => $update->channelPost->text,
            UpdateTypeEnum::EDITED_CHANNEL_POST => $update->editedChannelPost->text,
            UpdateTypeEnum::INLINE_QUERY => $update->inlineQuery->query,
            UpdateTypeEnum::CHOSEN_INLINE_RESULT => $update->chosenInlineResult->query,
            UpdateTypeEnum::CALLBACK_QUERY => $update->callbackQuery->data,
            UpdateTypeEnum::BUSINESS_MESSAGE => $update->businessMessage->text,
            UpdateTypeEnum::EDIT_BUSINESS_MESSAGE => $update->editedBusinessMessage->text,
            default => null,
        };
    }
}
