<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades;

use DateTimeImmutable;
use Illuminate\Support\Facades\Facade;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Phptg\BotApi\FailResult;
use Phptg\BotApi\Method\AnswerCallbackQuery;
use Phptg\BotApi\Method\DeleteChatPhoto;
use Phptg\BotApi\Method\DeleteChatStickerSet;
use Phptg\BotApi\Method\DeleteForumTopic;
use Phptg\BotApi\Method\DeleteMyCommands;
use Phptg\BotApi\Method\EditChatInviteLink;
use Phptg\BotApi\Method\EditChatSubscriptionInviteLink;
use Phptg\BotApi\Method\EditForumTopic;
use Phptg\BotApi\Method\EditGeneralForumTopic;
use Phptg\BotApi\Method\EditMessageChecklist;
use Phptg\BotApi\Method\EditStory;
use Phptg\BotApi\Method\Game\SendGame;
use Phptg\BotApi\Method\Payment\EditUserStarSubscription;
use Phptg\BotApi\Method\Payment\SendInvoice;
use Phptg\BotApi\Method\SendAnimation;
use Phptg\BotApi\Method\SendAudio;
use Phptg\BotApi\Method\SendChatAction;
use Phptg\BotApi\Method\SendChecklist;
use Phptg\BotApi\Method\SendContact;
use Phptg\BotApi\Method\SendDice;
use Phptg\BotApi\Method\SendDocument;
use Phptg\BotApi\Method\SendLocation;
use Phptg\BotApi\Method\SendMediaGroup;
use Phptg\BotApi\Method\SendMessage;
use Phptg\BotApi\Method\SendPaidMedia;
use Phptg\BotApi\Method\SendPhoto;
use Phptg\BotApi\Method\SendPoll;
use Phptg\BotApi\Method\SendVenue;
use Phptg\BotApi\Method\SendVideo;
use Phptg\BotApi\Method\SendVideoNote;
use Phptg\BotApi\Method\SendVoice;
use Phptg\BotApi\Method\Sticker\DeleteStickerFromSet;
use Phptg\BotApi\Method\Sticker\DeleteStickerSet;
use Phptg\BotApi\Method\Sticker\SendGift;
use Phptg\BotApi\Method\Sticker\SendSticker;
use Phptg\BotApi\Method\UpdatingMessage\DeleteBusinessMessages;
use Phptg\BotApi\Method\UpdatingMessage\DeleteMessage;
use Phptg\BotApi\Method\UpdatingMessage\DeleteMessages;
use Phptg\BotApi\Method\UpdatingMessage\EditMessageCaption;
use Phptg\BotApi\Method\UpdatingMessage\EditMessageLiveLocation;
use Phptg\BotApi\Method\UpdatingMessage\EditMessageMedia;
use Phptg\BotApi\Method\UpdatingMessage\EditMessageReplyMarkup;
use Phptg\BotApi\Method\UpdatingMessage\EditMessageText;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\BotCommandScope;
use Phptg\BotApi\Type\ChatInviteLink;
use Phptg\BotApi\Type\ForceReply;
use Phptg\BotApi\Type\InlineKeyboardMarkup;
use Phptg\BotApi\Type\InputChecklist;
use Phptg\BotApi\Type\InputFile;
use Phptg\BotApi\Type\InputMedia;
use Phptg\BotApi\Type\InputMediaAudio;
use Phptg\BotApi\Type\InputMediaDocument;
use Phptg\BotApi\Type\InputMediaPhoto;
use Phptg\BotApi\Type\InputMediaVideo;
use Phptg\BotApi\Type\InputPaidMedia;
use Phptg\BotApi\Type\InputPollOption;
use Phptg\BotApi\Type\InputStoryContent;
use Phptg\BotApi\Type\LinkPreviewOptions;
use Phptg\BotApi\Type\Message;
use Phptg\BotApi\Type\MessageEntity;
use Phptg\BotApi\Type\Payment\LabeledPrice;
use Phptg\BotApi\Type\ReplyKeyboardMarkup;
use Phptg\BotApi\Type\ReplyKeyboardRemove;
use Phptg\BotApi\Type\ReplyParameters;
use Phptg\BotApi\Type\Story;
use Phptg\BotApi\Type\StoryArea;
use Phptg\BotApi\Type\SuggestedPostParameters;
use SensitiveParameter;

/**
 * @mixin TelegramBotApi
 */
class SpiritBox extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TelegramBotApi::class;
    }

    /**
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @see https://core.telegram.org/bots/api#sendanimation
     * @link TelegramBotApi::sendAnimation()
     */
    public static function sendAnimation(
        InputFile|string $animation,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $duration = null,
        ?int $width = null,
        ?int $height = null,
        InputFile|string|null $thumbnail = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $hasSpoiler = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendAnimation(
                $chatId ?? Extrasense::chat()->id,
                $animation,
                $businessConnectionId,
                $messageThreadId,
                $duration,
                $width,
                $height,
                $thumbnail,
                $caption,
                $parseMode,
                $captionEntities,
                $showCaptionAboveMedia,
                $hasSpoiler,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @see https://core.telegram.org/bots/api#sendaudio
     * @link TelegramBotApi::sendAudio()
     */
    public static function sendAudio(
        string|InputFile $audio,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?int $duration = null,
        ?string $performer = null,
        ?string $title = null,
        string|InputFile|null $thumbnail = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendAudio(
                $chatId ?? Extrasense::chat()->id,
                $audio,
                $businessConnectionId,
                $messageThreadId,
                $caption,
                $parseMode,
                $captionEntities,
                $duration,
                $performer,
                $title,
                $thumbnail,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendchataction
     * @link TelegramBotApi::sendChatAction()
     */
    public static function sendChatAction(
        string $action,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
    ): FailResult|true {
        return self::call(
            new SendChatAction(
                $chatId ?? Extrasense::chat()->id,
                $action,
                $businessConnectionId,
                $messageThreadId,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendcontact
     * @link TelegramBotApi::sendContact()
     */
    public static function sendContact(
        string $phoneNumber,
        string $firstName,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $lastName = null,
        ?string $vcard = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendContact(
                $chatId ?? Extrasense::chat()->id,
                $phoneNumber,
                $firstName,
                $businessConnectionId,
                $messageThreadId,
                $lastName,
                $vcard,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendchecklist
     * @link TelegramBotApi::sendChecklist()
     */
    public static function sendChecklist(
        string $businessConnectionId,
        InputChecklist $checklist,
        ?int $chatId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
    ): FailResult|Message {
        return self::call(
            new SendChecklist(
                $businessConnectionId,
                $chatId ?? Extrasense::chat()->id,
                $checklist,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#senddice
     * @link TelegramBotApi::sendDice()
     */
    public static function sendDice(
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $emoji = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendDice(
                $chatId ?? Extrasense::chat()->id,
                $businessConnectionId,
                $messageThreadId,
                $emoji,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @see https://core.telegram.org/bots/api#senddocument
     * @link TelegramBotApi::sendDocument()
     */
    public static function sendDocument(
        string|InputFile $document,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        string|InputFile|null $thumbnail = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $disableContentTypeDetection = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendDocument(
                $chatId ?? Extrasense::chat()->id,
                $document,
                $businessConnectionId,
                $messageThreadId,
                $thumbnail,
                $caption,
                $parseMode,
                $captionEntities,
                $disableContentTypeDetection,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendgame
     * @link TelegramBotApi::sendGame()
     */
    public static function sendGame(
        string $gameShortName,
        ?int $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
    ): FailResult|Message {
        return self::call(
            new SendGame(
                $chatId ?? Extrasense::chat()->id,
                $gameShortName,
                $businessConnectionId,
                $messageThreadId,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendgift
     *
     * @param  MessageEntity[]|null  $textEntities
     *
     * @link TelegramBotApi::sendGift()
     */
    public static function sendGift(
        string $giftId,
        ?int $userId = null,
        ?string $text = null,
        ?string $textParseMode = null,
        ?array $textEntities = null,
        ?bool $payForUpgrade = null,
        int|string|null $chatId = null,
    ): FailResult|true {
        return self::call(
            new SendGift(
                $userId ?? Extrasense::user()->id,
                $giftId,
                $text,
                $textParseMode,
                $textEntities,
                $payForUpgrade,
                $chatId ?? Extrasense::chat()->id
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendinvoice
     *
     * @param  LabeledPrice[]  $prices
     * @param  int[]|null  $suggestedTipAmounts
     *
     * @link TelegramBotApi::sendInvoice()
     */
    public static function sendInvoice(
        string $title,
        string $description,
        string $payload,
        string $currency,
        array $prices,
        null|int|string $chatId = null,
        ?int $messageThreadId = null,
        #[SensitiveParameter]
        ?string $providerToken = null,
        ?int $maxTipAmount = null,
        ?array $suggestedTipAmounts = null,
        ?string $startParameter = null,
        ?string $providerData = null,
        ?string $photoUrl = null,
        ?int $photoSize = null,
        ?int $photoWidth = null,
        ?int $photoHeight = null,
        ?bool $needName = null,
        ?bool $needPhoneNumber = null,
        ?bool $needEmail = null,
        ?bool $needShippingAddress = null,
        ?bool $sendPhoneNumberToProvider = null,
        ?bool $sendEmailToProvider = null,
        ?bool $isFlexible = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendInvoice(
                $chatId ?? Extrasense::chat()->id,
                $title,
                $description,
                $payload,
                $currency,
                $prices,
                $messageThreadId,
                $providerToken,
                $maxTipAmount,
                $suggestedTipAmounts,
                $startParameter,
                $providerData,
                $photoUrl,
                $photoSize,
                $photoWidth,
                $photoHeight,
                $needName,
                $needPhoneNumber,
                $needEmail,
                $needShippingAddress,
                $sendPhoneNumberToProvider,
                $sendEmailToProvider,
                $isFlexible,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendlocation
     * @link TelegramBotApi::sendLocation()
     */
    public static function sendLocation(
        float $latitude,
        float $longitude,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?float $horizontalAccuracy = null,
        ?int $livePeriod = null,
        ?int $heading = null,
        ?int $proximityAlertRadius = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendLocation(
                $chatId ?? Extrasense::chat()->id,
                $latitude,
                $longitude,
                $businessConnectionId,
                $messageThreadId,
                $horizontalAccuracy,
                $livePeriod,
                $heading,
                $proximityAlertRadius,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendmediagroup
     *
     * @param  InputMediaAudio[]|InputMediaDocument[]|InputMediaPhoto[]|InputMediaVideo[]  $media
     * @return FailResult|Message[]
     *
     * @link TelegramBotApi::sendMediaGroup()
     */
    public static function sendMediaGroup(
        array $media,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
    ): FailResult|array {
        return self::call(
            new SendMediaGroup(
                $chatId ?? Extrasense::chat()->id,
                $media,
                $businessConnectionId,
                $messageThreadId,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $allowPaidBroadcast,
                $directMessagesTopicId,
            ),
        );
    }

    /**
     * @param  MessageEntity[]|null  $entities
     *
     * @see https://core.telegram.org/bots/api#sendmessage
     * @link TelegramBotApi::sendMessage()
     */
    public static function sendMessage(
        string $text,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $parseMode = null,
        ?array $entities = null,
        ?LinkPreviewOptions $linkPreviewOptions = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendMessage(
                $chatId ?? Extrasense::chat()->id,
                $text,
                $businessConnectionId,
                $messageThreadId,
                $parseMode,
                $entities,
                $linkPreviewOptions,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendpaidmedia
     *
     * @param  InputPaidMedia[]  $media
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @link TelegramBotApi::sendPaidMedia()
     */
    public static function sendPaidMedia(
        int $starCount,
        array $media,
        null|int|string $chatId = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?string $businessConnectionId = null,
        ?string $payload = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
        ?int $messageThreadId = null,
    ): FailResult|Message {
        return self::call(
            new SendPaidMedia(
                $chatId ?? Extrasense::chat()->id,
                $starCount,
                $media,
                $caption,
                $parseMode,
                $captionEntities,
                $showCaptionAboveMedia,
                $disableNotification,
                $protectContent,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $businessConnectionId,
                $payload,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
                $messageThreadId,
            ),
        );
    }

    /**
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @see https://core.telegram.org/bots/api#sendphoto
     * @link TelegramBotApi::sendPhoto()
     */
    public static function sendPhoto(
        string|InputFile $photo,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $hasSpoiler = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendPhoto(
                $chatId ?? Extrasense::chat()->id,
                $photo,
                $businessConnectionId,
                $messageThreadId,
                $caption,
                $parseMode,
                $captionEntities,
                $showCaptionAboveMedia,
                $hasSpoiler,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @param  InputPollOption[]  $options
     * @param  MessageEntity[]|null  $questionEntities
     * @param  MessageEntity[]|null  $explanationEntities
     *
     * @see https://core.telegram.org/bots/api#sendpoll
     * @link TelegramBotApi::sendPoll()
     */
    public static function sendPoll(
        string $question,
        array $options,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $questionParseMode = null,
        ?array $questionEntities = null,
        ?bool $isAnonymous = null,
        ?string $type = null,
        ?bool $allowsMultipleAnswers = null,
        ?int $correctOptionId = null,
        ?string $explanation = null,
        ?string $explanationParseMode = null,
        ?array $explanationEntities = null,
        ?int $openPeriod = null,
        ?DateTimeImmutable $closeDate = null,
        ?bool $isClosed = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
    ): FailResult|Message {
        return self::call(
            new SendPoll(
                $chatId ?? Extrasense::chat()->id,
                $question,
                $options,
                $businessConnectionId,
                $messageThreadId,
                $questionParseMode,
                $questionEntities,
                $isAnonymous,
                $type,
                $allowsMultipleAnswers,
                $correctOptionId,
                $explanation,
                $explanationParseMode,
                $explanationEntities,
                $openPeriod,
                $closeDate,
                $isClosed,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendsticker
     * @link TelegramBotApi::sendSticker()
     */
    public static function sendSticker(
        InputFile|string $sticker,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $emoji = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendSticker(
                $chatId ?? Extrasense::chat()->id,
                $sticker,
                $businessConnectionId,
                $messageThreadId,
                $emoji,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendvenue
     * @link TelegramBotApi::sendVenue()
     */
    public static function sendVenue(
        float $latitude,
        float $longitude,
        string $title,
        string $address,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $foursquareId = null,
        ?string $foursquareType = null,
        ?string $googlePlaceId = null,
        ?string $googlePlaceType = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendVenue(
                $chatId ?? Extrasense::chat()->id,
                $latitude,
                $longitude,
                $title,
                $address,
                $businessConnectionId,
                $messageThreadId,
                $foursquareId,
                $foursquareType,
                $googlePlaceId,
                $googlePlaceType,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @see https://core.telegram.org/bots/api#sendvideo
     * @link TelegramBotApi::sendVideo()
     */
    public static function sendVideo(
        string|InputFile $video,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $duration = null,
        ?int $width = null,
        ?int $height = null,
        string|InputFile|null $thumbnail = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        ?bool $hasSpoiler = null,
        ?bool $supportsStreaming = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        string|InputFile|null $cover = null,
        ?int $startTimestamp = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendVideo(
                $chatId ?? Extrasense::chat()->id,
                $video,
                $businessConnectionId,
                $messageThreadId,
                $duration,
                $width,
                $height,
                $thumbnail,
                $caption,
                $parseMode,
                $captionEntities,
                $showCaptionAboveMedia,
                $hasSpoiler,
                $supportsStreaming,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $cover,
                $startTimestamp,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#sendvideonote
     * @link TelegramBotApi::sendVideoNote()
     */
    public static function sendVideoNote(
        string|InputFile $videoNote,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?int $duration = null,
        ?int $length = null,
        string|InputFile|null $thumbnail = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendVideoNote(
                $chatId ?? Extrasense::chat()->id,
                $videoNote,
                $businessConnectionId,
                $messageThreadId,
                $duration,
                $length,
                $thumbnail,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @see https://core.telegram.org/bots/api#sendvoice
     * @link TelegramBotApi::sendVoice()
     */
    public static function sendVoice(
        string|InputFile $voice,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?int $duration = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendVoice(
                $chatId ?? Extrasense::chat()->id,
                $voice,
                $businessConnectionId,
                $messageThreadId,
                $caption,
                $parseMode,
                $captionEntities,
                $duration,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                $replyParameters,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editchatinvitelink
     * @link TelegramBotApi::editChatInviteLink()
     */
    public static function editChatInviteLink(
        string $inviteLink,
        null|int|string $chatId = null,
        ?string $name = null,
        ?DateTimeImmutable $expireDate = null,
        ?int $memberLimit = null,
        ?bool $createsJoinRequest = null,
    ): FailResult|ChatInviteLink {
        return self::call(
            new EditChatInviteLink($chatId ?? Extrasense::chat()->id, $inviteLink, $name, $expireDate, $memberLimit, $createsJoinRequest),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editchatsubscriptioninvitelink
     * @link TelegramBotApi::editChatSubscriptionInviteLink()
     */
    public static function editChatSubscriptionInviteLink(
        string $inviteLink,
        null|int|string $chatId = null,
        ?string $name = null,
    ): FailResult|ChatInviteLink {
        return self::call(
            new EditChatSubscriptionInviteLink($chatId ?? Extrasense::chat()->id, $inviteLink, $name),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editforumtopic
     * @link TelegramBotApi::editForumTopic()
     */
    public static function editForumTopic(
        int $messageThreadId,
        null|int|string $chatId = null,
        ?string $name = null,
        ?string $iconCustomEmojiId = null,
    ): FailResult|true {
        return self::call(
            new EditForumTopic($chatId ?? Extrasense::chat()->id, $messageThreadId, $name, $iconCustomEmojiId),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editgeneralforumtopic
     * @link TelegramBotApi::editGeneralForumTopic()
     */
    public static function editGeneralForumTopic(string $name, null|int|string $chatId = null): FailResult|true
    {
        return self::call(
            new EditGeneralForumTopic($chatId ?? Extrasense::chat()->id, $name),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editmessagecaption
     *
     * @param  MessageEntity[]|null  $captionEntities
     *
     * @link TelegramBotApi::editMessageCaption()
     */
    public static function editMessageCaption(
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?bool $showCaptionAboveMedia = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
    ): FailResult|Message|true {
        return self::call(
            new EditMessageCaption(
                $businessConnectionId,
                $chatId ?? Extrasense::chat()->id,
                $messageId ?? Extrasense::message()->messageId,
                $inlineMessageId,
                $caption,
                $parseMode,
                $captionEntities,
                $showCaptionAboveMedia,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editmessagelivelocation
     * @link TelegramBotApi::editMessageLiveLocation()
     */
    public static function editMessageLiveLocation(
        float $latitude,
        float $longitude,
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?int $livePeriod = null,
        ?float $horizontalAccuracy = null,
        ?int $heading = null,
        ?int $proximityAlertRadius = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
    ): FailResult|Message|true {
        return self::call(
            new EditMessageLiveLocation(
                $latitude,
                $longitude,
                $businessConnectionId,
                $chatId ?? Extrasense::chat()->id,
                $messageId ?? Extrasense::message()->messageId,
                $inlineMessageId,
                $livePeriod,
                $horizontalAccuracy,
                $heading,
                $proximityAlertRadius,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editmessagemedia
     * @link TelegramBotApi::editMessageMedia()
     */
    public static function editMessageMedia(
        InputMedia $media,
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
    ): FailResult|Message|true {
        return self::call(
            new EditMessageMedia(
                $media,
                $businessConnectionId,
                $chatId ?? Extrasense::chat()->id,
                $messageId ?? Extrasense::message()->messageId,
                $inlineMessageId,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editmessagechecklist
     * @link TelegramBotApi::editMessageChecklist()
     */
    public static function editMessageChecklist(
        string $businessConnectionId,
        InputChecklist $checklist,
        ?int $chatId = null,
        ?int $messageId = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
    ): FailResult|Message {
        return self::call(
            new EditMessageChecklist(
                $businessConnectionId,
                $chatId ?? Extrasense::chat()->id,
                $messageId ?? Extrasense::message()->messageId,
                $checklist,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editmessagereplymarkup
     * @link TelegramBotApi::editMessageReplyMarkup()
     */
    public static function editMessageReplyMarkup(
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
    ): FailResult|Message|true {
        return self::call(
            new EditMessageReplyMarkup(
                $businessConnectionId,
                $chatId ?? Extrasense::chat()->id,
                $messageId ?? Extrasense::message()->messageId,
                $inlineMessageId,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editmessagetext
     *
     * @param  MessageEntity[]|null  $entities
     *
     * @link TelegramBotApi::editMessageText()
     */
    public static function editMessageText(
        string $text,
        ?string $businessConnectionId = null,
        int|string|null $chatId = null,
        ?int $messageId = null,
        ?string $inlineMessageId = null,
        ?string $parseMode = null,
        ?array $entities = null,
        ?LinkPreviewOptions $linkPreviewOptions = null,
        null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null,
    ): FailResult|Message|true {
        return self::call(
            new EditMessageText(
                $text,
                $businessConnectionId,
                $chatId ?? Extrasense::chat()->id,
                $messageId ?? Extrasense::message()->messageId,
                $inlineMessageId,
                $parseMode,
                $entities,
                $linkPreviewOptions,
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#editstory
     *
     * @param  MessageEntity[]|null  $captionEntities
     * @param  StoryArea[]|null  $areas
     *
     * @link TelegramBotApi::editStory()
     */
    public static function editStory(
        string $businessConnectionId,
        int $storyId,
        InputStoryContent $content,
        ?string $caption = null,
        ?string $parseMode = null,
        ?array $captionEntities = null,
        ?array $areas = null,
    ): FailResult|Story {
        return self::call(
            new EditStory(
                $businessConnectionId,
                $storyId,
                $content,
                $caption,
                $parseMode,
                $captionEntities,
                $areas,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#edituserstarsubscription
     * @link TelegramBotApi::editUserStarSubscription()
     */
    public static function editUserStarSubscription(
        string $telegramPaymentChargeId,
        bool $isCanceled,
        ?int $userId = null,
    ): FailResult|true {
        return self::call(
            new EditUserStarSubscription(
                $userId ?? Extrasense::user()->id,
                $telegramPaymentChargeId,
                $isCanceled,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#deletebusinessmessages
     *
     * @param  int[]  $messageIds
     */
    public static function deleteBusinessMessages(
        string $businessConnectionId,
        array $messageIds,
    ): FailResult|true {
        return self::call(
            new DeleteBusinessMessages($businessConnectionId, $messageIds),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#deletechatphoto
     */
    public static function deleteChatPhoto(int|string $chatId): FailResult|true
    {
        return self::call(
            new DeleteChatPhoto($chatId),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#deletechatstickerset
     */
    public static function deleteChatStickerSet(int|string $chatId): FailResult|true
    {
        return self::call(
            new DeleteChatStickerSet($chatId),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#deleteforumtopic
     */
    public static function deleteForumTopic(int $messageThreadId, null|int|string $chatId = null): FailResult|true
    {
        return self::call(new DeleteForumTopic($chatId ?? Extrasense::chat()->id, $messageThreadId));
    }

    /**
     * @see https://core.telegram.org/bots/api#deletemessage
     */
    public static function deleteMessage(null|int|string $chatId = null, ?int $messageId = null): FailResult|true
    {
        return self::call(new DeleteMessage($chatId ?? Extrasense::chat()->id, $messageId ?? Extrasense::message()->messageId));
    }

    /**
     * @see https://core.telegram.org/bots/api#deletemessages
     *
     * @param  int[]  $messageIds
     */
    public static function deleteMessages(array $messageIds, null|int|string $chatId = null): FailResult|true
    {
        return self::call(new DeleteMessages($chatId ?? Extrasense::chat()->id, $messageIds));
    }

    /**
     * @see https://core.telegram.org/bots/api#deletemycommands
     */
    public static function deleteMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null): FailResult|true
    {
        return self::call(new DeleteMyCommands($scope, $languageCode));
    }

    /**
     * @see https://core.telegram.org/bots/api#deletestickerfromset
     */
    public static function deleteStickerFromSet(string $sticker): FailResult|true
    {
        return self::call(
            new DeleteStickerFromSet($sticker),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#deletestickerset
     */
    public static function deleteStickerSet(string $name): FailResult|true
    {
        return self::call(new DeleteStickerSet($name));
    }

    /**
     * @param  MessageEntity[]|null  $entities
     */
    public static function replyMessage(
        string $text,
        null|int|string $chatId = null,
        ?string $businessConnectionId = null,
        ?int $messageThreadId = null,
        ?string $parseMode = null,
        ?array $entities = null,
        ?LinkPreviewOptions $linkPreviewOptions = null,
        ?bool $disableNotification = null,
        ?bool $protectContent = null,
        ?string $messageEffectId = null,
        ?ReplyParameters $replyParameters = null,
        KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        ?bool $allowPaidBroadcast = null,
        ?int $directMessagesTopicId = null,
        ?SuggestedPostParameters $suggestedPostParameters = null,
    ): FailResult|Message {
        return self::call(
            new SendMessage(
                $chatId ?? Extrasense::chat()->id,
                $text,
                $businessConnectionId,
                $messageThreadId,
                $parseMode,
                $entities,
                $linkPreviewOptions,
                $disableNotification,
                $protectContent,
                $messageEffectId,
                new ReplyParameters(Extrasense::message()->messageId),
                $replyMarkup instanceof KeyboardBuilderInterface ? $replyMarkup->build() : $replyMarkup,
                $allowPaidBroadcast,
                $directMessagesTopicId,
                $suggestedPostParameters,
            ),
        );
    }

    /**
     * @see https://core.telegram.org/bots/api#answercallbackquery
     * @link TelegramBotApi::answerCallbackQuery()
     */
    public function answerCallbackQuery(
        ?string $callbackQueryId = null,
        ?string $text = null,
        ?bool $showAlert = null,
        ?string $url = null,
        ?int $cacheTime = null,
    ): FailResult|true {
        return $this->call(
            new AnswerCallbackQuery($callbackQueryId ?? Extrasense::update()->callbackQuery->id, $text, $showAlert, $url, $cacheTime),
        );
    }
}
