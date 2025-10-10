<?php

use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Core\Router\Keyboard\InlineKeyboardBuilder;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardFactoryInterface;
use Lowel\Telepath\Facades\Telepath;
use Lowel\Telepath\TelegramAppFactoryInterface;
use Vjik\TelegramBot\Api\Type\Chat;
use Vjik\TelegramBot\Api\Type\Message;
use Vjik\TelegramBot\Api\Type\Update\Update;
use Vjik\TelegramBot\Api\Type\User;

function telegramApp()
{
    return App::make(TelegramAppFactoryInterface::class)->webhook();
}

test('message', function (): void {
    $this->updatesMockBuilder
        ->addMessage($this->name())
        ->mock();

    Telepath::onMessage(function (Message $message, Chat $chat, User $user) {
        expect($message->text)->toEqual($this->name())
            ->and($chat)->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('message edited', function (): void {
    $this->updatesMockBuilder
        ->addEditedMessage($this->name())
        ->mock();

    Telepath::onMessageEdit(function (Message $message, Chat $chat, User $user) {
        expect($message->text)->toEqual($this->name())
            ->and($chat)->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('channel post', function (): void {
    $this->updatesMockBuilder
        ->addChannelPost($this->name())
        ->mock();

    Telepath::onChannelPost(function (Message $message, Chat $chat) {
        expect($message->text)->toEqual($this->name());
    });

    telegramApp()->start();
});

test('channel post edited', function (): void {
    $this->updatesMockBuilder
        ->addEditedChannelPost($this->name())
        ->mock();

    Telepath::onChannelPostEdit(function (Message $message, Chat $chat) {
        expect($message->text)->toEqual($this->name())
            ->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('business connection', function (): void {
    $this->updatesMockBuilder
        ->addBusinessConnection()
        ->mock();

    Telepath::onBusinessConnection(function (User $user) {
        expect(true)->toEqual(true)
            ->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('business message', function (): void {
    $this->updatesMockBuilder
        ->addBusinessMessage($this->name())
        ->mock();

    Telepath::onBusinessMessage(function (Message $message, User $user, Chat $chat) {
        expect($message->text)->toEqual($this->name())
            ->and($chat)->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('edit business message', function (): void {
    $this->updatesMockBuilder
        ->addEditedBusinessMessage($this->name())
        ->mock();

    Telepath::onBusinessMessageEdit(function (Message $message, User $user, Chat $chat) {
        expect($message->text)->toEqual($this->name())
            ->and($chat)->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('deleted business message', function (): void {
    $this->updatesMockBuilder
        ->addDeletedBusinessMessages()
        ->mock();

    Telepath::onBusinessMessagesDelete(function (Chat $chat) {
        expect(true)->toEqual(true)
            ->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('message reaction', function (): void {
    $this->updatesMockBuilder
        ->addMessageReaction()
        ->mock();

    Telepath::onMessageReaction(function (Update $update, Chat $chat, User $user) {
        expect($update->messageReaction->newReaction[0]->emoji)->toEqual('👍')
            ->and($chat)->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('message reaction count', function (): void {
    $this->updatesMockBuilder
        ->addMessageReactionCount()
        ->mock();

    Telepath::onMessageReactionCount(function (Update $update, Chat $chat) {
        expect($update->messageReactionCount->reactions[0]->type->emoji)->toEqual('👍')
            ->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('inline query', function (): void {
    $this->updatesMockBuilder
        ->addInlineQuery($this->name())
        ->mock();

    Telepath::onInlineQuery(function (Update $update, User $user) {
        expect($update->inlineQuery->query)->toEqual($this->name())
            ->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('inline query result', function (): void {
    $this->updatesMockBuilder
        ->addChosenInlineResult($this->name())
        ->mock();

    Telepath::onInlineQueryChosenResult(function (Update $update, User $user) {
        expect($update->chosenInlineResult->query)->toEqual($this->name())
            ->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('callback query', function (): void {
    $this->updatesMockBuilder
        ->addCallbackQuery($this->name())
        ->mock();

    Telepath::onCallbackQuery(function (Update $update, User $user) {
        expect($update->callbackQuery->data)->toEqual($this->name())
            ->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('shipping query', function (): void {
    $this->updatesMockBuilder
        ->addShippingQuery()
        ->mock();

    Telepath::onShippingQuery(function (Update $update, User $user) {
        expect($update->shippingQuery)->not()->toBeNull()
            ->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('pre checkout query', function (): void {
    $this->updatesMockBuilder
        ->addPreCheckoutQuery()
        ->mock();

    Telepath::onPreCheckoutQuery(function (Update $update, User $user) {
        expect($update->preCheckoutQuery)->not()->toBeNull()
            ->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('purchased paid media', function (): void {
    $this->updatesMockBuilder
        ->addPurchasedPaidMedia()
        ->mock();

    Telepath::onPurchasedPaidMedia(function (Update $update, User $user) {
        expect($update->purchasedPaidMedia)->not()->toBeNull()
            ->and($user)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('poll', function (): void {
    $this->updatesMockBuilder
        ->addPoll()
        ->mock();

    Telepath::onPoll(function (Update $update) {
        expect($update->poll)->not()->toBeNull();
    });

    telegramApp()->start();
});

test('poll answer', function (): void {
    $this->updatesMockBuilder
        ->addPollAnswer()
        ->mock();

    Telepath::onPollAnswer(function (Update $update, Chat $chat, User $user) {
        expect($update->pollAnswer)->not()->toBeNull()
            ->and($user)->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('my chat member', function (): void {
    $this->updatesMockBuilder
        ->addMyChatMember()
        ->mock();

    Telepath::onMyChatMemberUpdate(function (Update $update, Chat $chat, User $user) {
        expect($update->myChatMember)->not()->toBeNull()
            ->and($user)->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('chat member', function (): void {
    $this->updatesMockBuilder
        ->addChatMember()
        ->mock();

    Telepath::onChatMemberUpdate(function (Update $update, Chat $chat, User $user) {
        expect($update->chatMember)->not()->toBeNull()
            ->and($user)->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('chat join request', function (): void {
    $this->updatesMockBuilder
        ->addChatJoinRequest()
        ->mock();

    Telepath::onChatJoinRequest(function (Update $update, Chat $chat, User $user) {
        expect($update->chatJoinRequest)->not()->toBeNull()
            ->and($user)->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('chat boost', function (): void {
    $this->updatesMockBuilder
        ->addChatBoost()
        ->mock();

    Telepath::onChatBoost(function (Update $update, Chat $chat, User $user) {
        expect($update->chatBoost)->not()->toBeNull()
            ->and($user)->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('chat boost remove', function (): void {
    $this->updatesMockBuilder
        ->addRemovedChatBoost()
        ->mock();

    Telepath::onChatBoostRemove(function (Update $update, Chat $chat) {
        expect($update->removedChatBoost)->not()->toBeNull()
            ->and($chat)->not()->toEqual(null);
    });

    telegramApp()->start();
});

test('keyboards', function (): void {
    $testFactory = new class implements KeyboardFactoryInterface
    {
        public function make(): KeyboardBuilderInterface
        {
            return (new InlineKeyboardBuilder)->row(new class extends AbstractCallbackButton
            {
                public function handle(): callable
                {
                    return function (Update $update) {
                        expect($update)->not()->toBeNull();
                    };
                }

                public function text(array $args = []): string
                {
                    return 'test';
                }

                public function callbackDataId(array $args = []): string
                {
                    return 'test';
                }
            });
        }
    };

    $this->updatesMockBuilder
        ->addCallbackQuery('test')
        ->mock();

    Telepath::keyboard($testFactory::class);

    telegramApp()->start();
});
