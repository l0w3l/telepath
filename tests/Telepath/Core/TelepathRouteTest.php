<?php

use Lowel\Telepath\Facades\Telepath;
use Phptg\BotApi\Type\Chat;
use Phptg\BotApi\Type\Message;
use Phptg\BotApi\Type\Update\Update;
use Phptg\BotApi\Type\User;

test('command routes matching command text through Laravel router', function (): void {
    $this->updatesMockBuilder->addMessage('/'.$this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onCommand(function (Message $message, Chat $chat, User $user): void {
            expect($message->text)->toEqual('/'.$this->name())
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        }, $this->name());
    });

    $this->dispatchTelegramUpdates();
});

test('command can match bot username suffix', function (): void {
    config()->set('telepath.profiles.default.username', 'TestBot');
    $this->updatesMockBuilder->addMessage('/'.$this->name().'@TestBot');

    $this->withinTelegramRoutes(function (): void {
        Telepath::onCommand(function (Message $message, Chat $chat, User $user): void {
            expect($message->text)->toEqual('/'.$this->name().'@TestBot')
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        }, $this->name().'@TestBot');
    });

    $this->dispatchTelegramUpdates();
});

test('message routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addMessage($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onMessage(function (Message $message, Chat $chat, User $user): void {
            expect($message->text)->toEqual($this->name())
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('edited message routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addEditedMessage($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onMessageEdit(function (Message $message, Chat $chat, User $user): void {
            expect($message->text)->toEqual($this->name())
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('channel post routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addChannelPost($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onChannelPost(function (Message $message, Chat $chat): void {
            expect($message->text)->toEqual($this->name())
                ->and($chat)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('edited channel post routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addEditedChannelPost($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onChannelPostEdit(function (Message $message, Chat $chat): void {
            expect($message->text)->toEqual($this->name())
                ->and($chat)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('business connection routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addBusinessConnection();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onBusinessConnection(function (User $user): void {
            expect($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('business message routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addBusinessMessage($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onBusinessMessage(function (Message $message, User $user, Chat $chat): void {
            expect($message->text)->toEqual($this->name())
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('edited business message routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addEditedBusinessMessage($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onBusinessMessageEdit(function (Message $message, User $user, Chat $chat): void {
            expect($message->text)->toEqual($this->name())
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('deleted business messages route through Laravel router', function (): void {
    $this->updatesMockBuilder->addDeletedBusinessMessages();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onBusinessMessagesDelete(function (Chat $chat): void {
            expect($chat)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('message reaction routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addMessageReaction();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onMessageReaction(function (Update $update, Chat $chat, User $user): void {
            expect($update->messageReaction->newReaction[0]->emoji)->toEqual('👍')
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('message reaction count routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addMessageReactionCount();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onMessageReactionCount(function (Update $update, Chat $chat): void {
            expect($update->messageReactionCount->reactions[0]->type->emoji)->toEqual('👍')
                ->and($chat)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('inline query routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addInlineQuery($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onInlineQuery(function (Update $update, User $user): void {
            expect($update->inlineQuery->query)->toEqual($this->name())
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('chosen inline result routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addChosenInlineResult($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onInlineQueryChosenResult(function (Update $update, User $user): void {
            expect($update->chosenInlineResult->query)->toEqual($this->name())
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('callback query routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addCallbackQuery($this->name());

    $this->withinTelegramRoutes(function (): void {
        Telepath::onCallbackQuery(function (Update $update, User $user): void {
            expect($update->callbackQuery->data)->toEqual($this->name())
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('shipping query routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addShippingQuery();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onShippingQuery(function (Update $update, User $user): void {
            expect($update->shippingQuery)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('pre checkout query routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addPreCheckoutQuery();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onPreCheckoutQuery(function (Update $update, User $user): void {
            expect($update->preCheckoutQuery)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('purchased paid media routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addPurchasedPaidMedia();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onPurchasedPaidMedia(function (Update $update, User $user): void {
            expect($update->purchasedPaidMedia)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('poll routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addPoll();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onPoll(function (Update $update): void {
            expect($update->poll)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('poll answer routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addPollAnswer();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onPollAnswer(function (Update $update, Chat $chat, User $user): void {
            expect($update->pollAnswer)->not()->toBeNull()
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('my chat member routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addMyChatMember();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onMyChatMemberUpdate(function (Update $update, Chat $chat, User $user): void {
            expect($update->myChatMember)->not()->toBeNull()
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('chat member routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addChatMember();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onChatMemberUpdate(function (Update $update, Chat $chat, User $user): void {
            expect($update->chatMember)->not()->toBeNull()
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('chat join request routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addChatJoinRequest();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onChatJoinRequest(function (Update $update, Chat $chat, User $user): void {
            expect($update->chatJoinRequest)->not()->toBeNull()
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('chat boost routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addChatBoost();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onChatBoost(function (Update $update, Chat $chat, User $user): void {
            expect($update->chatBoost)->not()->toBeNull()
                ->and($chat)->not()->toBeNull()
                ->and($user)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('removed chat boost routes through Laravel router', function (): void {
    $this->updatesMockBuilder->addRemovedChatBoost();

    $this->withinTelegramRoutes(function (): void {
        Telepath::onChatBoostRemove(function (Update $update, Chat $chat): void {
            expect($update->removedChatBoost)->not()->toBeNull()
                ->and($chat)->not()->toBeNull();
        });
    });

    $this->dispatchTelegramUpdates();
});

test('button registers callback query route through Laravel router', function (): void {
    $this->updatesMockBuilder->addCallbackQuery('test-button');

    $this->withinTelegramRoutes(function (): void {
        Telepath::button(function (Update $update): void {
            expect($update->callbackQuery->data)->toEqual('test-button');
        }, 'test-button');
    });

    $this->dispatchTelegramUpdates();
});
