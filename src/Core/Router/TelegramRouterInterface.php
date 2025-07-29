<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * @phpstan-type RouterHandler callable(TelegramBotApi, Update): mixed
 * @phpstan-type MiddlewareHandler callable(TelegramBotApi, Update, callable): mixed
 * @phpstan-type FallbackHandler callable(TelegramBotApi): mixed
 */
interface TelegramRouterInterface
{
    /**
     * Registers a handler for the 'update' type.
     *
     * @param  callable(TelegramBotApi, Update): mixed  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onMessage(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'edited_message' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onMessageEdit(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'message' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#messagereactionupdated
     */
    public function onMessageReaction(callable $handler): void;

    /**
     * Registers a handler for the 'message_reaction_count' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#messagereactioncountupdated
     */
    public function onMessageReactionCount(callable $handler): void;

    /**
     * Registers a handler for the 'channel_post' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onChannelPost(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'edited_channel_post' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onChannelPostEdit(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'business_connection' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#businessconnection
     */
    public function onBusinessConnection(callable $handler): void;

    /**
     * Registers a handler for the 'business_message' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onBusinessMessage(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'business_message_edit' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onBusinessMessageEdit(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'business_message_delete' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#businessmessagesdeleted
     */
    public function onBusinessMessagesDelete(callable $handler): void;

    /**
     * Registers a handler for the 'callback_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#callbackquery
     */
    public function onCallbackQuery(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'inline_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#inlinequery
     */
    public function onInlineQuery(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'inline_query_chosen_result' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#choseninlineresult
     */
    public function onInlineQueryChosenResult(callable $handler, ?string $pattern = null): void;

    /**
     * Registers a handler for the 'shipping_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#shippingquery
     */
    public function onShippingQuery(callable $handler): void;

    /**
     * Registers a handler for the 'pre_checkout_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#precheckoutquery
     */
    public function onPreCheckoutQuery(callable $handler): void;

    /**
     * Registers a handler for the 'purchased_paid_media' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#paidmediapurchased
     */
    public function onPurchasedPaidMedia(callable $handler): void;

    /**
     * Registers a handler for the 'chat_member' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onPoll(callable $handler): void;

    /**
     * Registers a handler for the 'poll_answer' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#pollanswer
     */
    public function onPollAnswer(callable $handler): void;

    /**
     * Registers a handler for the 'chat_join_request' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatjoinrequest
     */
    public function onChatJoinRequest(callable $handler): void;

    /**
     * Registers a handler for the 'chat_member' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onChatMemberUpdate(callable $handler): void;

    /**
     * Registers a handler for the 'chat_boost' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatboost
     */
    public function onChatBoost(callable $handler): void;

    /**
     * Registers a handler for the 'chat_boost_remove' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatboostremoved
     */
    public function onChatBoostRemove(callable $handler): void;

    /**
     * Registers a handler for the 'my_chat_member' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onMyChatMemberUpdate(callable $handler): void;

    /**
     * Main method to register a handler for a specific pattern and update type.
     * This method allows you to define how the bot should respond to different types of updates
     * and patterns.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#update
     */
    public function on(?string $pattern, callable $handler, UpdateTypeEnum $type = UpdateTypeEnum::MESSAGE): void;

    /**
     * Groups a set of handlers together, allowing for shared middleware and context.
     * This method is useful for organizing related handlers and applying common logic.
     */
    public function group(callable $callback): void;

    /**
     * Registers a fallback handler that will be called if no other handlers match.
     * This is useful for handling unexpected updates or providing default behavior.
     *
     * @param  FallbackHandler  $handler
     */
    public function fallback(callable $handler): void;

    /**
     * Registers a middleware that will be applied to all handlers.
     * This allows you to add common logic that should run before or after the handler.
     *
     * @param  MiddlewareHandler  $handler
     * @return $this
     */
    public function middleware(callable $handler): self;
}
