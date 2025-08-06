<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Lowel\Telepath\Core\Router\Context\RouteContextInterface;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerInterface;
use Lowel\Telepath\Core\Router\Middleware\TelegramMiddlewareInterface;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

/**
 * @extends  RouteContextInterface<TelegramRouterInterface>
 *
 * @phpstan-type RouterHandler class-string<TelegramHandlerInterface>|callable(TelegramBotApi, Update): mixed
 * @phpstan-type MiddlewareHandler array<class-string<TelegramMiddlewareInterface>|callable(TelegramBotApi, Update, callable): mixed>|class-string<TelegramMiddlewareInterface>|callable(TelegramBotApi, Update, callable): void
 * @phpstan-type FallbackHandler RouterHandler
 */
interface TelegramRouterInterface extends RouteContextInterface
{
    /**
     * Registers a handler for the 'update' type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onMessage(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'edited_message' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onMessageEdit(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'message' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#messagereactionupdated
     */
    public function onMessageReaction(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'message_reaction_count' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#messagereactioncountupdated
     */
    public function onMessageReactionCount(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'channel_post' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onChannelPost(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'edited_channel_post' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onChannelPostEdit(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'business_connection' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#businessconnection
     */
    public function onBusinessConnection(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'business_message' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onBusinessMessage(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'business_message_edit' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onBusinessMessageEdit(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'business_message_delete' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#businessmessagesdeleted
     */
    public function onBusinessMessagesDelete(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'callback_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#callbackquery
     */
    public function onCallbackQuery(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'inline_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#inlinequery
     */
    public function onInlineQuery(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'inline_query_chosen_result' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#choseninlineresult
     */
    public function onInlineQueryChosenResult(string|callable $handler, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a handler for the 'shipping_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#shippingquery
     */
    public function onShippingQuery(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'pre_checkout_query' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#precheckoutquery
     */
    public function onPreCheckoutQuery(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'purchased_paid_media' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#paidmediapurchased
     */
    public function onPurchasedPaidMedia(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'chat_member' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onPoll(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'poll_answer' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#pollanswer
     */
    public function onPollAnswer(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'chat_join_request' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatjoinrequest
     */
    public function onChatJoinRequest(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'chat_member' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onChatMemberUpdate(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'chat_boost' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatboost
     */
    public function onChatBoost(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'chat_boost_remove' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatboostremoved
     */
    public function onChatBoostRemove(string|callable $handler): RouteContextInterface;

    /**
     * Registers a handler for the 'my_chat_member' update type.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onMyChatMemberUpdate(string|callable $handler): RouteContextInterface;

    /**
     * Main method to register a handler for a specific pattern and update type.
     * This method allows you to define how the bot should respond to different types of updates
     * and patterns.
     *
     * @param  RouterHandler  $handler
     *
     * @link https://core.telegram.org/bots/api#update
     */
    public function on(string|callable $handler, UpdateTypeEnum $type = UpdateTypeEnum::MESSAGE, ?string $pattern = null): RouteContextInterface;

    /**
     * Registers a fallback handler that will be called if no other handlers match.
     * This is useful for handling unexpected updates or providing default behavior.
     *
     * @param  FallbackHandler  $handler
     */
    public function fallback(string|callable $handler): void;

    /**
     * Groups a set of handlers together, allowing for shared middleware and context.
     * This method is useful for organizing related handlers and applying common logic.
     */
    public function group(callable $callback): RouteContextInterface;
}
