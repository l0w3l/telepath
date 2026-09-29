<?php

use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Core\Router\Keyboard\InlineKeyboardBuilder;
use Lowel\Telepath\Core\Router\Keyboard\ReplyKeyboardBuilder;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\MethodInterface;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Chat;
use Phptg\BotApi\Type\EphemeralMessageParameters;
use Phptg\BotApi\Type\InputPollOption;
use Phptg\BotApi\Type\InputRichMessage;
use Phptg\BotApi\Type\Message;

beforeEach(function (): void {
    $this->api = new class
    {
        public ?MethodInterface $method = null;

        public function call(MethodInterface $method): Message|true
        {
            $this->method = $method;

            if (str_ends_with($method->getApiMethod(), 'Draft')) {
                return true;
            }

            return new Message(1, new DateTimeImmutable, new Chat(123, 'private'));
        }
    };

    SpiritBox::swap($this->api);
});

afterEach(function (): void {
    SpiritBox::clearResolvedInstance(TelegramBotApi::class);
});

test('poll wrapper preserves old option and accepts new poll settings', function (): void {
    SpiritBox::sendPoll('Question', [new InputPollOption('Yes')], chatId: 123, correctOptionId: 0, membersOnly: true);

    expect($this->api->method->getData())
        ->toHaveKey('correct_option_ids', [0])
        ->toHaveKey('members_only', true);
});

test('message wrapper serializes ephemeral recipient', function (): void {
    SpiritBox::sendMessage('Hello', chatId: 123, ephemeralMessageParameters: new EphemeralMessageParameters(456));

    expect($this->api->method->getData())->toHaveKey('ephemeral_message_parameters', ['receiver_user_id' => 456]);
});

test('rich message and live photo wrappers use new upstream methods', function (): void {
    SpiritBox::sendRichMessage(new InputRichMessage(html: '<b>Hello</b>'), chatId: 123);
    expect($this->api->method->getApiMethod())->toBe('sendRichMessage')
        ->and($this->api->method->getData())->toHaveKey('rich_message', ['html' => '<b>Hello</b>']);

    SpiritBox::sendLivePhoto('live-file-id', 'photo-file-id', chatId: 123);
    expect($this->api->method->getApiMethod())->toBe('sendLivePhoto')
        ->and($this->api->method->getData())->toHaveKey('live_photo', 'live-file-id');
});

test('draft wrapper forwards stop options', function (): void {
    SpiritBox::sendMessageDraft(9, 'Working', chatId: 123, canStop: true, keepOnStop: false);

    expect($this->api->method->getData())->toHaveKey('can_stop', true)
        ->toHaveKey('keep_on_stop', false);

    SpiritBox::sendRichMessageDraft(10, new InputRichMessage(markdown: '**Working**'), chatId: 123, canStop: true);
    expect($this->api->method->getApiMethod())->toBe('sendRichMessageDraft')
        ->and($this->api->method->getData())->toHaveKey('can_stop', true);
});

test('edit message text accepts a rich message without plain text', function (): void {
    SpiritBox::editMessageText(inlineMessageId: 'inline-1', richMessage: new InputRichMessage(html: '<b>Updated</b>'));

    expect($this->api->method->getData())->toHaveKey('rich_message', ['html' => '<b>Updated</b>']);
});

test('keyboard builders expose force reply and disabled buttons', function (): void {
    $button = new class extends AbstractCallbackButton
    {
        public function handle(): void {}
    };

    $button->setText('Unavailable')->setDisabled();

    expect($button->toButton()->toRequestArray())->toHaveKey('disabled', []);

    $inline = InlineKeyboardBuilder::create(forceReply: true);
    expect($inline->build()->toRequestArray())->toHaveKey('force_reply', true);

    $reply = ReplyKeyboardBuilder::create(forceReply: true);
    expect($reply->build()->toRequestArray())->toHaveKey('force_reply', true);
});
