<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\Context;

use Illuminate\Contracts\Foundation\Application;
use Lowel\Telepath\Config\Profile;
use Lowel\Telepath\Core\Components\AbstractComponent;
use Lowel\Telepath\Exceptions\ChatNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\MessageNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UserNotFoundInCurrentContextException;
use Phptg\BotApi\Type\Chat;
use Phptg\BotApi\Type\Message;
use Phptg\BotApi\Type\Update\Update;
use Phptg\BotApi\Type\User;

class Context extends AbstractComponent implements ContextInterface
{
    private ?Update $update = null;

    public static function register(Application $app): void
    {
        $app->singleton(Context::class, fn () => new self);
        $app->singleton(ContextInterface::class, fn ($app) => $app->make(Context::class));

        $app->bind(Chat::class, fn ($app) => $app->make(ContextInterface::class)->chat());
        $app->bind(Message::class, fn ($app) => $app->make(ContextInterface::class)->message());
        $app->bind(User::class, fn ($app) => $app->make(ContextInterface::class)->user());
        $app->bind(Update::class, fn ($app) => $app->make(ContextInterface::class)->update());
    }

    public function onBefore(Update $update): void
    {
        $this->update = $update;
    }

    public function onAfter(Update $update): void
    {
        $this->update = null;
    }

    public function update(): Update
    {
        return $this->update ?? throw new UpdateNotFoundInCurrentContextException('Update not found in current context');
    }

    public function user(): User
    {
        try {
            $update = $this->update();

            return
                $update->businessConnection->user
                ?? $update->messageReaction->user
                ?? $update->inlineQuery->from
                ?? $update->chosenInlineResult->from
                ?? $update->callbackQuery->from
                ?? $update->shippingQuery->from
                ?? $update->preCheckoutQuery->from
                ?? $update->pollAnswer->user
                ?? $update->chatMember->from
                ?? $update->myChatMember->from
                ?? $update->chatJoinRequest->from
                ?? $update->purchasedPaidMedia->from
                ?? $update->chatBoost?->boost?->source?->getUser()
                ?? throw new UserNotFoundInCurrentContextException('User not found in current context');
        } catch (UserNotFoundInCurrentContextException $e) {
            try {
                $message = $this->message();
            } catch (MessageNotFoundInCurrentContextException $e) {
                throw new UserNotFoundInCurrentContextException('User not found in current context', previous: $e);
            }

            return $message->from
                ?? $message->viaBot
                ?? $message->leftChatMember
                ?? $message->senderBusinessBot
                ?? throw new UserNotFoundInCurrentContextException('User not found in current context');
        }
    }

    public function message(): Message
    {
        $update = $this->update();

        return $update->message
            ?? $update->editedMessage
            ?? $update->businessMessage
            ?? $update->editedBusinessMessage
            ?? $update->channelPost
            ?? $update->editedChannelPost
            ?? $update->callbackQuery->message
            ?? throw new MessageNotFoundInCurrentContextException('Message not found in current context');
    }

    public function chat(): Chat
    {
        try {
            $message = $this->message();

            return $message->chat;
        } catch (MessageNotFoundInCurrentContextException $e) {
            $update = $this->update();

            return $update->deletedBusinessMessages->chat
                ?? $update->messageReaction->chat
                ?? $update->messageReactionCount->chat
                ?? $update->pollAnswer->voterChat
                ?? $update->chatMember->chat
                ?? $update->myChatMember->chat
                ?? $update->chatJoinRequest->chat
                ?? $update->chatBoost->chat
                ?? $update->removedChatBoost->chat
                ?? $update->messageReaction->actorChat
                ?? throw new ChatNotFoundInCurrentContextException('Chat not found in current context');
        }
    }

    public function profile(?string $profileKey = null): Profile
    {
        return new Profile($profileKey ?? config('telepath.profile'));
    }
}
