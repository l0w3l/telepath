<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Drivers\Traits;

use Illuminate\Support\Facades\Log;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerCollectionInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Exceptions\Router\TelegramHandlerNotFoundException;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

trait UpdateHandlerTrait
{
    protected function processUpdate(Update $update, TelegramBotApi $telegramBotApi, TelegramHandlerCollectionInterface $handlersCollection): void
    {
        try {
            $types = UpdateTypeEnum::resolve($update);

            $handlers = [];
            foreach ($types as $type) {
                $text = $this->extractText($update, $type);

                $handlers = array_merge($handlers, $handlersCollection->getHandlersBy($type, $text));
            }

            if (empty($handlers)) {
                throw new TelegramHandlerNotFoundException('Update type not found');
            }
        } catch (TelegramHandlerNotFoundException $e) {
            Log::debug('Telegram Handler not found for update', $update->getRaw(true) ?? [print_r($update, true)]);

            $handlers = $handlersCollection->getFallbacks();
        }

        foreach ($handlers as $handler) {
            $handler($telegramBotApi, $update);
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
