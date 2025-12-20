<?php

declare(strict_types=1);

namespace Lowel\Telepath\Enums;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Vjik\TelegramBot\Api\Type\Update\Update;

enum UpdateTypeEnum: string
{
    case MESSAGE = 'message';
    case EDITED_MESSAGE = 'edited_message';
    case CHANNEL_POST = 'channel_post';
    case EDITED_CHANNEL_POST = 'edited_channel_post';
    case BUSINESS_CONNECTION = 'business_connection';
    case BUSINESS_MESSAGE = 'business_message';
    case EDIT_BUSINESS_MESSAGE = 'edited_business_message';
    case DELETE_BUSINESS_MESSAGES = 'deleted_business_messages';
    case MESSAGE_REACTION = 'message_reaction';
    case MESSAGE_REACTION_COUNT = 'message_reaction_count';
    case INLINE_QUERY = 'inline_query';
    case CHOSEN_INLINE_RESULT = 'chosen_inline_result';
    case CALLBACK_QUERY = 'callback_query';
    case SHIPPING_QUERY = 'shipping_query';
    case PRE_CHECKOUT_QUERY = 'pre_checkout_query';
    case PURCHASED_PAID_MEDIA = 'purchased_paid_media';
    case POLL = 'poll';
    case POLL_ANSWER = 'poll_answer';
    case MY_CHAT_MEMBER = 'my_chat_member';
    case CHAT_MEMBER = 'chat_member';
    case CHAT_JOIN_REQUEST = 'chat_join_request';
    case CHAT_BOOST = 'chat_boost';
    case REMOVED_CHAT_BOOST = 'removed_chat_boost';

    public static function resolve(Update $update): array
    {
        $types = [];

        foreach (self::cases() as $type) {
            if ($update->{Str::camel($type->value)}) {
                $types[] = $type;
            }
        }

        return $types;
    }

    public static function extractText(Update $update, UpdateTypeEnum $type): ?string
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

    /**
     * @return string[]
     */
    public static function toArray(array $filter = ['*']): array
    {
        $array = array_map(fn (UpdateTypeEnum $enum) => $enum->value, self::cases());

        if (in_array('*', $filter)) {
            return $array;
        } else if (in_array('auto', $filter)) {
            $routerResolver = App::make(TelegramRouterResolverInterface::class);

            return $routerResolver->getExecutors()->getAllUpdateTypes();
        } else {
            return array_filter($array, fn (string $value) => in_array($value, $filter));
        }
    }
}
