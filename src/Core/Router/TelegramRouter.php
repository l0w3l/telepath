<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Closure;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Reply\AbstractReplyButton;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Facades\Extrasense;
use Phptg\BotApi\Type\Update\Update;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class TelegramRouter implements TelegramRouterInterface
{
    public function onCommand(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        if ($pattern === null) {
            if (is_array($handler) === false || null === $pattern = $this->resolvePatternFromControllerSignature($handler)) {
                throw new RuntimeException('Command pattern not found');
            }
        }

        if (str_starts_with($pattern, '/')) {
            $pattern = substr($pattern, 1);
        }

        return $this->createRule(UpdateTypeEnum::MESSAGE, $handler, $pattern);
    }

    public function onMessage(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::MESSAGE, $handler, $pattern);
    }

    public function onMessageEdit(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::EDITED_MESSAGE, $handler, $pattern);
    }

    public function onChannelPost(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::CHANNEL_POST, $handler, $pattern);
    }

    public function onMessageReaction(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::MESSAGE_REACTION, $handler);
    }

    public function onMessageReactionCount(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::MESSAGE_REACTION_COUNT, $handler);
    }

    public function onChannelPostEdit(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::EDITED_CHANNEL_POST, $handler, $pattern);
    }

    public function onBusinessConnection(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::BUSINESS_CONNECTION, $handler);
    }

    public function onBusinessMessage(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::BUSINESS_MESSAGE, $handler, $pattern);
    }

    public function onBusinessMessageEdit(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::EDIT_BUSINESS_MESSAGE, $handler, $pattern);
    }

    public function onBusinessMessagesDelete(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::DELETE_BUSINESS_MESSAGES, $handler);
    }

    public function onInlineQueryChosenResult(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::CHOSEN_INLINE_RESULT, $handler, $pattern);
    }

    public function onShippingQuery(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::SHIPPING_QUERY, $handler);
    }

    public function onPreCheckoutQuery(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::PRE_CHECKOUT_QUERY, $handler);
    }

    public function onPurchasedPaidMedia(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::PURCHASED_PAID_MEDIA, $handler);
    }

    public function onPoll(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::POLL, $handler);
    }

    public function onPollAnswer(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::POLL_ANSWER, $handler);
    }

    public function onChatJoinRequest(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::CHAT_JOIN_REQUEST, $handler);
    }

    public function onChatMemberUpdate(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::CHAT_MEMBER, $handler);
    }

    public function onChatBoost(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::CHAT_BOOST, $handler);
    }

    public function onChatBoostRemove(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::REMOVED_CHAT_BOOST, $handler);
    }

    public function onMyChatMemberUpdate(string|callable|Closure|array $handler): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::MY_CHAT_MEMBER, $handler);
    }

    public function onCallbackQuery(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::CALLBACK_QUERY, $handler, $pattern);
    }

    public function onInlineQuery(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        return $this->createRule(UpdateTypeEnum::INLINE_QUERY, $handler, $pattern);
    }

    public function group(array|callable $attributes, ?callable $callback = null): Router
    {
        if ($attributes instanceof Closure) {
            $callback = $attributes;
            $attributes = [];
        }

        return Route::group($attributes, $callback);
    }

    public function button(string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        if (is_string($handler) && (is_subclass_of($handler, AbstractCallbackButton::class) || is_subclass_of($handler, AbstractReplyButton::class))) {
            return $handler::make()->resolve($this);
        }

        return $this->onCallbackQuery($handler, $pattern);
    }

    public function buttons(array $buttons): Router
    {
        return $this->group([], function () use ($buttons) {
            foreach ($buttons as $button) {
                if (is_array($button) && $controllerPattern = $this->resolvePatternFromControllerSignature($button)) {
                    $this->button($button, $controllerPattern);
                } elseif (is_string($button)) {
                    $this->button($button);
                } else {
                    $this->button($button[0], $button[1]);
                }
            }
        });
    }

    public function redirect(string $data = '', ?Update $update = null, ?UpdateTypeEnum $updateTypeEnum = null): Response
    {
        $request = RequestFactory::fromRaw($update ?? Extrasense::update(), $updateTypeEnum ?? Extrasense::type(), $data);

        return Route::dispatch($request);
    }

    protected function createRule(UpdateTypeEnum $updateTypeEnum, string|callable|Closure|array $handler, ?string $pattern = null): \Illuminate\Routing\Route
    {
        if ($pattern === null) {
            return Route::post(sprintf('%s/{any?}', $updateTypeEnum->value), $handler);
        } else {
            $randArg = chr(random_int(97, 122)).strtolower(Str::random(7));

            return Route::post(sprintf('%s/{%s}', $updateTypeEnum->value, $randArg), $handler)
                ->where($randArg, $pattern);
        }
    }

    protected function resolvePatternFromControllerSignature(array $controllerSignature): ?string
    {
        if (is_string($controllerSignature[0]) && class_exists($controllerSignature[0])) {
            return $controllerSignature[1];
        }

        return null;
    }
}
