<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers\Traits;

use Illuminate\Support\Facades\Log;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

trait UpdateHandlerTrait
{
    protected function processUpdate(Update $update, TelegramBotApi $telegramBotApi, TelegramRouterResolverInterface $routerResolver): void
    {
        try {
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
                throw new TelegramHandlerNotFoundException('Update type not found');
            }
        } catch (TelegramHandlerNotFoundException $e) {
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
