<?php

declare(strict_types=1);

namespace Lowel\Telepath\Tests\Mock\Support;

use Illuminate\Support\Facades\App;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Tests\Mock\TestAppDriver;
use Vjik\TelegramBot\Api\Type\Update\Update;

class TelegramUpdatesMock
{
    private array $result = [];

    private int $nextUpdateId = 1000;

    private function nextId(): int
    {
        return $this->nextUpdateId++;
    }

    private function user(array $overrides = []): array
    {
        return array_merge([
            'id' => rand(1000, 999999),
            'is_bot' => false,
            'first_name' => 'TestUser',
        ], $overrides);
    }

    private function chat(array $overrides = []): array
    {
        return array_merge([
            'id' => rand(-1000000000, 1000000000),
            'type' => 'private',
        ], $overrides);
    }

    private function add(UpdateTypeEnum $type, array $payload): self
    {
        $this->result[] = [
            'update_id' => $this->nextId(),
            $type->value => $payload,
        ];

        return $this;
    }

    // ======= Методы для каждого типа =======
    public function addMessage(string $text): self
    {
        return $this->add(UpdateTypeEnum::MESSAGE, [
            'message_id' => rand(1, 9999),
            'from' => $this->user(),
            'chat' => $this->chat(),
            'date' => time(),
            'text' => $text,
        ]);
    }

    public function addEditedMessage(string $newText): self
    {
        return $this->add(UpdateTypeEnum::EDITED_MESSAGE, [
            'message_id' => rand(1, 9999),
            'from' => $this->user(),
            'chat' => $this->chat(),
            'date' => time() - 60,
            'edit_date' => time(),
            'text' => $newText,
        ]);
    }

    public function addChannelPost(string $text): self
    {
        return $this->add(UpdateTypeEnum::CHANNEL_POST, [
            'message_id' => rand(1, 9999),
            'chat' => $this->chat(['type' => 'channel']),
            'date' => time(),
            'text' => $text,
        ]);
    }

    public function addEditedChannelPost(string $text): self
    {
        return $this->add(UpdateTypeEnum::EDITED_CHANNEL_POST, [
            'message_id' => rand(1, 9999),
            'chat' => $this->chat(['type' => 'channel']),
            'date' => time() - 100,
            'edit_date' => time(),
            'text' => $text,
        ]);
    }

    public function addBusinessConnection(): self
    {
        return $this->add(UpdateTypeEnum::BUSINESS_CONNECTION, [
            'id' => uniqid(),
            'user' => $this->user(),
            'user_chat_id' => rand(1, 9999),
            'date' => now()->unix(),
            'rights' => [
                'can_reply' => true,
                'can_read_messages' => true,
                'can_delete_sent_messages' => true,
                'can_delete_all_messages' => true,
                'can_edit_name' => true,
                'can_edit_bio' => true,
                'can_edit_profile_photo' => true,
                'can_edit_username' => true,
                'can_change_gift_settings' => true,
                'can_view_gifts_and_stars' => true,
                'can_convert_gifts_to_stars' => true,
                'can_transfer_and_upgrade_gifts' => true,
                'can_transfer_stars' => true,
                'can_manage_stories' => true,
            ],
            'is_enabled' => true,
        ]);
    }

    public function addBusinessMessage(string $text): self
    {
        return $this->add(UpdateTypeEnum::BUSINESS_MESSAGE, [
            'message_id' => rand(1, 9999),
            'from' => $this->user(),
            'business_connection_id' => uniqid(),
            'chat' => $this->chat(['type' => 'business']),
            'date' => time(),
            'text' => $text,
        ]);
    }

    public function addEditedBusinessMessage(string $text): self
    {
        return $this->add(UpdateTypeEnum::EDIT_BUSINESS_MESSAGE, [
            'message_id' => rand(1, 9999),
            'from' => $this->user(),
            'business_connection_id' => uniqid(),
            'chat' => $this->chat(['type' => 'business']),
            'date' => time(),
            'text' => $text,
        ]);
    }

    public function addDeletedBusinessMessages(): self
    {
        return $this->add(UpdateTypeEnum::DELETE_BUSINESS_MESSAGES, [
            'business_connection_id' => uniqid(),
            'chat' => $this->chat(['type' => 'business']),
            'message_ids' => [1, 2, 3],
        ]);
    }

    public function addMessageReaction(): self
    {
        return $this->add(UpdateTypeEnum::MESSAGE_REACTION, [
            'message_id' => rand(1, 9999),
            'chat' => $this->chat(),
            'user' => $this->user(),
            'actor_chat' => $this->chat(),
            'date' => time(),
            'new_reaction' => [['type' => 'emoji', 'emoji' => '👍']],
            'old_reaction' => [],
        ]);
    }

    public function addMessageReactionCount(): self
    {
        return $this->add(UpdateTypeEnum::MESSAGE_REACTION_COUNT, [
            'chat' => $this->chat(),
            'message_id' => rand(1, 9999),
            'date' => time(),
            'reactions' => [
                ['type' => ['type' => 'emoji', 'emoji' => '👍'], 'total_count' => 3],
                ['type' => ['type' => 'emoji', 'emoji' => '❤'], 'total_count' => 3],
            ],
        ]);
    }

    public function addInlineQuery(string $query): self
    {
        return $this->add(UpdateTypeEnum::INLINE_QUERY, [
            'id' => uniqid(),
            'from' => $this->user(),
            'query' => $query,
            'offset' => '',
        ]);
    }

    public function addChosenInlineResult(string $query): self
    {
        return $this->add(UpdateTypeEnum::CHOSEN_INLINE_RESULT, [
            'result_id' => uniqid(),
            'from' => $this->user(),
            'query' => $query,
        ]);
    }

    public function addCallbackQuery(string $data): self
    {
        return $this->add(UpdateTypeEnum::CALLBACK_QUERY, [
            'id' => uniqid(),
            'chat_instance' => uniqid(),
            'from' => $this->user(),
            'data' => $data,
        ]);
    }

    public function addShippingQuery(): self
    {
        return $this->add(UpdateTypeEnum::SHIPPING_QUERY, [
            'id' => uniqid(),
            'from' => $this->user(),
            'invoice_payload' => 'payload123',
            'shipping_address' => ['country_code' => 'US', 'city' => 'NY', 'state' => '', 'street_line1' => '', 'street_line2' => '', 'post_code' => ''],
        ]);
    }

    public function addPreCheckoutQuery(): self
    {
        return $this->add(UpdateTypeEnum::PRE_CHECKOUT_QUERY, [
            'id' => uniqid(),
            'from' => $this->user(),
            'currency' => 'USD',
            'total_amount' => 500,
            'invoice_payload' => 'payload123',
        ]);
    }

    public function addPurchasedPaidMedia(): self
    {
        return $this->add(UpdateTypeEnum::PURCHASED_PAID_MEDIA, [
            'from' => $this->user(),
            'paid_media_payload' => 'media123',
        ]);
    }

    public function addPoll(): self
    {
        return $this->add(UpdateTypeEnum::POLL, [
            'id' => uniqid(),
            'question' => 'Do you like this bot?',
            'total_voter_count' => rand(1, 9999),
            'is_anonymous' => false,
            'type' => 'quiz',
            'allows_multiple_answers' => false,
            'options' => [
                ['text' => 'Yes', 'voter_count' => 1],
                ['text' => 'No', 'voter_count' => 0],
            ],
            'is_closed' => false,
        ]);
    }

    public function addPollAnswer(): self
    {
        return $this->add(UpdateTypeEnum::POLL_ANSWER, [
            'poll_id' => uniqid(),
            'voter_chat' => $this->chat(),
            'user' => $this->user(),
            'option_ids' => [0],
        ]);
    }

    public function addMyChatMember(): self
    {
        return $this->add(UpdateTypeEnum::MY_CHAT_MEMBER, [
            'chat' => $this->chat(),
            'from' => $this->user(),
            'date' => time(),
            'old_chat_member' => ['user' => $this->user(), 'status' => 'member'],
            'new_chat_member' => ['user' => $this->user(), 'status' => 'kicked', 'until_date' => time()],
        ]);
    }

    public function addChatMember(): self
    {
        return $this->add(UpdateTypeEnum::CHAT_MEMBER, [
            'chat' => $this->chat(),
            'from' => $this->user(),
            'date' => time(),
            'old_chat_member' => ['user' => $this->user(), 'status' => 'member'],
            'new_chat_member' => ['user' => $this->user(), 'status' => 'kicked', 'until_date' => time()],
        ]);
    }

    public function addChatJoinRequest(): self
    {
        return $this->add(UpdateTypeEnum::CHAT_JOIN_REQUEST, [
            'chat' => $this->chat(),
            'from' => $this->user(),
            'user_chat_id' => (int) uniqid(),
            'date' => time(),
        ]);
    }

    public function addChatBoost(): self
    {
        return $this->add(UpdateTypeEnum::CHAT_BOOST, [
            'chat' => $this->chat(),
            'boost' => [
                'boost_id' => uniqid(),
                'from' => $this->user(),
                'expiration_date' => time(),
                'add_date' => time(),
                'source' => [
                    'source' => 'premium',
                    'user' => $this->user(),
                ],
            ],
        ]);
    }

    public function addRemovedChatBoost(): self
    {
        return $this->add(UpdateTypeEnum::REMOVED_CHAT_BOOST, [
            'chat' => $this->chat(),
            'boost_id' => uniqid(),
            'source' => [
                'source' => 'premium',
                'user' => $this->user(),
            ],
            'remove_date' => time(),
        ]);
    }

    public function getArray(): array
    {
        return $this->result;
    }

    public function getJson(): string
    {
        return json_encode($this->getArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function getUpdates(): array
    {
        $updates = [];

        foreach ($this->getArray() as $value) {
            $updates[] = Update::fromJson(
                json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        return $updates;
    }

    public function mock(): void
    {
        App::bind(TestAppDriver::class, fn () => new TestAppDriver($this->getUpdates()));
    }
}
