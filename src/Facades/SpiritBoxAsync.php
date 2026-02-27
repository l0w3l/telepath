<?php

declare(strict_types=1);

namespace Lowel\Telepath\Facades;

use DateTimeImmutable;
use Illuminate\Support\Facades\Facade;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Facades\Resources\AsyncSpiritBoxResource;
use Lowel\Telepath\Jobs\AsyncSpiritBoxRequestJob;
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
use Phptg\BotApi\Type\BotCommandScope;
use Phptg\BotApi\Type\ChatInviteLink;
use Phptg\BotApi\Type\ForceReply;
use Phptg\BotApi\Type\InlineKeyboardMarkup;
use Phptg\BotApi\Type\InputChecklist;
use Phptg\BotApi\Type\InputFile;
use Phptg\BotApi\Type\InputMedia;
use Phptg\BotApi\Type\InputStoryContent;
use Phptg\BotApi\Type\LinkPreviewOptions;
use Phptg\BotApi\Type\Message;
use Phptg\BotApi\Type\ReplyKeyboardMarkup;
use Phptg\BotApi\Type\ReplyKeyboardRemove;
use Phptg\BotApi\Type\ReplyParameters;
use Phptg\BotApi\Type\Story;
use Phptg\BotApi\Type\SuggestedPostParameters;

/**
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendAnimation(InputFile|string $animation, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendAudio(string|InputFile $audio, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?string $performer = null, ?string $title = null, string|InputFile|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> sendChatAction(string $action, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendContact(string $phoneNumber, string $firstName, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $lastName = null, ?string $vcard = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendChecklist(string $businessConnectionId, InputChecklist $checklist, ?int $chatId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendDice(null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendDocument(string|InputFile $document, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, string|InputFile|null $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $disableContentTypeDetection = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendGame(string $gameShortName, ?int $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null, ?bool $allowPaidBroadcast = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> sendGift(string $giftId, ?int $userId = null, ?string $text = null, ?string $textParseMode = null, ?array $textEntities = null, ?bool $payForUpgrade = null, int|string|null $chatId = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendInvoice(string $title, string $description, string $payload, string $currency, array $prices, null|int|string $chatId = null, ?int $messageThreadId = null, ?string $providerToken = null, ?int $maxTipAmount = null, ?array $suggestedTipAmounts = null, ?string $startParameter = null, ?string $providerData = null, ?string $photoUrl = null, ?int $photoSize = null, ?int $photoWidth = null, ?int $photoHeight = null, ?bool $needName = null, ?bool $needPhoneNumber = null, ?bool $needEmail = null, ?bool $needShippingAddress = null, ?bool $sendPhoneNumberToProvider = null, ?bool $sendEmailToProvider = null, ?bool $isFlexible = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendLocation(float $latitude, float $longitude, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?float $horizontalAccuracy = null, ?int $livePeriod = null, ?int $heading = null, ?int $proximityAlertRadius = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|array> sendMediaGroup(array $media, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendMessage(string $text, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendPaidMedia(int $starCount, array $media, null|int|string $chatId = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?string $businessConnectionId = null, ?string $payload = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null, ?int $messageThreadId = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendPhoto(string|InputFile $photo, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendPoll(string $question, array $options, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $questionParseMode = null, ?array $questionEntities = null, ?bool $isAnonymous = null, ?string $type = null, ?bool $allowsMultipleAnswers = null, ?int $correctOptionId = null, ?string $explanation = null, ?string $explanationParseMode = null, ?array $explanationEntities = null, ?int $openPeriod = null, ?DateTimeImmutable $closeDate = null, ?bool $isClosed = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendSticker(InputFile|string $sticker, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $emoji = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendVenue(float $latitude, float $longitude, string $title, string $address, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $foursquareId = null, ?string $foursquareType = null, ?string $googlePlaceId = null, ?string $googlePlaceType = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendVideo(string|InputFile $video, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $duration = null, ?int $width = null, ?int $height = null, string|InputFile|null $thumbnail = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, ?bool $hasSpoiler = null, ?bool $supportsStreaming = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, string|InputFile|null $cover = null, ?int $startTimestamp = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendVideoNote(string|InputFile $videoNote, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?int $duration = null, ?int $length = null, string|InputFile|null $thumbnail = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> sendVoice(string|InputFile $voice, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?int $duration = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|ChatInviteLink> editChatInviteLink(string $inviteLink, null|int|string $chatId = null, ?string $name = null, ?DateTimeImmutable $expireDate = null, ?int $memberLimit = null, ?bool $createsJoinRequest = null)
 * @method static AsyncSpiritBoxResource<FailResult|ChatInviteLink> editChatSubscriptionInviteLink(string $inviteLink, null|int|string $chatId = null, ?string $name = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> editForumTopic(int $messageThreadId, null|int|string $chatId = null, ?string $name = null, ?string $iconCustomEmojiId = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> editGeneralForumTopic(string $name, null|int|string $chatId = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message|true> editMessageCaption(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?bool $showCaptionAboveMedia = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message|true> editMessageLiveLocation(float $latitude, float $longitude, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?int $livePeriod = null, ?float $horizontalAccuracy = null, ?int $heading = null, ?int $proximityAlertRadius = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message|true> editMessageMedia(InputMedia $media, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message> editMessageChecklist(string $businessConnectionId, InputChecklist $checklist, ?int $chatId = null, ?int $messageId = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message|true> editMessageReplyMarkup(?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null)
 * @method static AsyncSpiritBoxResource<FailResult|Message|true> editMessageText(string $text, ?string $businessConnectionId = null, int|string|null $chatId = null, ?int $messageId = null, ?string $inlineMessageId = null, ?string $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, null|KeyboardBuilderInterface|InlineKeyboardMarkup $replyMarkup = null)
 * @method static AsyncSpiritBoxResource<FailResult|Story> editStory(string $businessConnectionId, int $storyId, InputStoryContent $content, ?string $caption = null, ?string $parseMode = null, ?array $captionEntities = null, ?array $areas = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> editUserStarSubscription(string $telegramPaymentChargeId, bool $isCanceled, ?int $userId = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteBusinessMessages(string $businessConnectionId, array $messageIds)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteChatPhoto(int|string $chatId)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteChatStickerSet(int|string $chatId)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteForumTopic(int $messageThreadId, null|int|string $chatId = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteMessage(null|int|string $chatId = null, ?int $messageId = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteMessages(array $messageIds, null|int|string $chatId = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteMyCommands(?BotCommandScope $scope = null, ?string $languageCode = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteStickerFromSet(string $sticker)
 * @method static AsyncSpiritBoxResource<FailResult|true> deleteStickerSet(string $name)
 * @method static AsyncSpiritBoxResource<FailResult|Message> replyMessage(string $text, null|int|string $chatId = null, ?string $businessConnectionId = null, ?int $messageThreadId = null, ?string $parseMode = null, ?array $entities = null, ?LinkPreviewOptions $linkPreviewOptions = null, ?bool $disableNotification = null, ?bool $protectContent = null, ?string $messageEffectId = null, ?ReplyParameters $replyParameters = null, KeyboardBuilderInterface|InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null, ?bool $allowPaidBroadcast = null, ?int $directMessagesTopicId = null, ?SuggestedPostParameters $suggestedPostParameters = null)
 * @method static AsyncSpiritBoxResource<FailResult|true> answerCallbackQuery(?string $callbackQueryId = null, ?string $text = null, ?bool $showAlert = null, ?string $url = null, ?int $cacheTime = null)
 */
class SpiritBoxAsync extends Facade
{
    public static function __callStatic($method, $args)
    {
        if (method_exists(SpiritBox::class, $method)) {
            $job = AsyncSpiritBoxRequestJob::create(
                Extrasense::update(), $method, $args
            );

            dispatch($job);

            return new AsyncSpiritBoxResource($job);
        }

        return parent::__callStatic($method, $args);
    }

    public static function getFacadeAccessor()
    {
        return SpiritBox::class;
    }
}
