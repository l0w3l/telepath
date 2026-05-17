<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

interface TelegramRouterInterface
{
    /**
     * Registers a handler for command patterns.
     *
     * @param  class-string|callable|Closure|array  $handler
     */
    public function onCommand(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'update' type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onMessage(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'edited_message' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onMessageEdit(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'channel_post' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onChannelPost(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'message' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#messagereactionupdated
     */
    public function onMessageReaction(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'message_reaction_count' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#messagereactioncountupdated
     */
    public function onMessageReactionCount(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'edited_channel_post' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onChannelPostEdit(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'business_connection' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#businessconnection
     */
    public function onBusinessConnection(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'business_message' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onBusinessMessage(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'business_message_edit' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#message
     */
    public function onBusinessMessageEdit(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'business_message_delete' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#businessmessagesdeleted
     */
    public function onBusinessMessagesDelete(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'callback_query' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#callbackquery
     */
    public function onCallbackQuery(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'inline_query' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#inlinequery
     */
    public function onInlineQuery(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'inline_query_chosen_result' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#choseninlineresult
     */
    public function onInlineQueryChosenResult(string|callable|Closure|array $handler, ?string $pattern = null): Route;

    /**
     * Registers a handler for the 'shipping_query' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#shippingquery
     */
    public function onShippingQuery(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'pre_checkout_query' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#precheckoutquery
     */
    public function onPreCheckoutQuery(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'purchased_paid_media' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#paidmediapurchased
     */
    public function onPurchasedPaidMedia(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'chat_member' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#poll
     */
    public function onPoll(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'poll_answer' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#pollanswer
     */
    public function onPollAnswer(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'chat_join_request' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#chatjoinrequest
     */
    public function onChatJoinRequest(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'chat_member' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onChatMemberUpdate(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'chat_boost' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#chatboost
     */
    public function onChatBoost(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'chat_boost_remove' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#chatboostremoved
     */
    public function onChatBoostRemove(string|callable|Closure|array $handler): Route;

    /**
     * Registers a handler for the 'my_chat_member' update type.
     *
     * @param  class-string|callable|Closure|array  $handler
     *
     * @link https://core.telegram.org/bots/api#chatmemberupdated
     */
    public function onMyChatMemberUpdate(string|callable|Closure|array $handler): Route;

    /**
     * Groups a set of handlers together, allowing for shared middleware and context.
     * This method is useful for organizing related handlers and applying common logic.
     */
    public function group(array $attributes, callable $callback): Router;

    public function button(string|callable|Closure|array $handler, string $pattern): Route;

    /**
     * Register buttons to the router
     */
    public function buttons(array $buttons): Router;
}
