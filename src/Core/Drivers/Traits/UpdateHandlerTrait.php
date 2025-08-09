<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers\Traits;

use Illuminate\Support\Facades\Log;
use Lowel\Telepath\Core\Router\Conversation\ConversationStorage;
use Lowel\Telepath\Core\Router\Conversation\ConversationStorageFactory;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Throwable;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

trait UpdateHandlerTrait
{
    protected function processUpdate(Update $update, TelegramBotApi $telegramBotApi, TelegramRouterResolverInterface $routerResolver): void
    {
        $conversationStorage = (new ConversationStorageFactory)->create($update);

        if ($update->message && $conversationStorage->hasActiveConversation()) {
            $this->resolveConversation($conversationStorage, $telegramBotApi, $update);
        } else {
            $this->resolveUpdate($update, $routerResolver, $telegramBotApi);
        }
    }

    public function resolveConversation(ConversationStorage $conversationStorage, TelegramBotApi $telegramBotApi, Update $update): void
    {
        $promise = $conversationStorage->popPromise();
        $shared = $conversationStorage->getShared();

        try {
            try {
                $shared = $promise->resolve($telegramBotApi, $update, $shared);
            } catch (Throwable $error) {
                $shared = $promise->reject($telegramBotApi, $update, $error, $shared);
            }

            $conversationStorage->storeShared($shared, $promise);
        } catch (Throwable $error) {
            Log::error($error->getMessage(), $error->getTrace());

            $conversationStorage->delete();
        }
    }

    public function resolveUpdate(Update $update, TelegramRouterResolverInterface $routerResolver, TelegramBotApi $telegramBotApi): void
    {
        $types = UpdateTypeEnum::resolve($update);
        $executors = [];

        foreach ($types as $type) {
            $text = $this->extractText($update, $type);

            foreach ($routerResolver->getHandlers() as $executor) {
                if ($executor->match($type, $text)) {
                    $executors[] = $executor;
                }
            }
        }

        if (empty($executors)) {
            Log::debug('Telegram Handler not found for update', $update->getRaw(true) ?? [print_r($update, true)]);

            $executors = $routerResolver->getFallbacks();
        }

        foreach ($executors as $handler) {
            $handler->proceed($telegramBotApi, $update);
        }
    }

    protected function extractText(Update $update, UpdateTypeEnum $type): ?string
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
