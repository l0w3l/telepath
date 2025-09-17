<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards;

use Closure;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonHandler;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonMetadata;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Traits\HashAbleTrait;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use ReflectionClass;
use ReflectionMethod;
use Vjik\TelegramBot\Api\Type\InlineKeyboardMarkup;

/**
 * @implements KeyboardInterface<InlineKeyboardMarkup>
 */
abstract class AbstractInlineKeyboard implements KeyboardInterface
{
    use HashAbleTrait, InvokeAbleTrait;

    /**
     * @return ButtonMetadata[]
     */
    public static function getButtonsMetadata(): array
    {
        $reflection = new ReflectionClass(static::class);

        $buttonMetadataCollection = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // only child methods
            if ($method->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            $buttonMetadata = ButtonMetadata::fromReflectionMethod($method);

            if ($buttonMetadata !== null) {
                $buttonMetadataCollection[] = $buttonMetadata;
            }
        }

        return $buttonMetadataCollection;
    }

    public static function build(array $args = []): InlineKeyboardMarkup
    {
        $instance = App::make(static::class);
        $buttonMetadataCollection = self::getButtonsMetadata();

        $buttons = [[]];
        foreach ($buttonMetadataCollection as $buttonMetadata) {
            $dynamicText = null;

            if (method_exists($instance, $buttonMetadata->getTextMethodName())) {
                $dynamicText = self::invokeStaticClassWithArgs($instance, $args, $buttonMetadata->getTextMethodName());
            }

            $inlineButton = $buttonMetadata->metadata->toButton($buttonMetadata->name, $dynamicText);

            if ($buttonMetadata->metadata->direction === 'row') {
                $buttons[array_key_last($buttons)][] = $inlineButton;
            } elseif ($buttonMetadata->metadata->direction === 'col') {
                $buttons[][] = $inlineButton;
            }
        }

        return new InlineKeyboardMarkup($buttons);
    }

    public static function handlers(): array
    {
        $handlers = [];

        foreach (static::getButtonsMetadata() as $buttonMetadata) {
            $instance = App::make(static::class);
            $meta = $buttonMetadata->metadata;

            $handlers[] = new ButtonHandler(
                Closure::fromCallable([$instance, $buttonMetadata->name]),
                $meta->getCallbackData($buttonMetadata->name),
                UpdateTypeEnum::CALLBACK_QUERY
            );
        }

        return $handlers;
    }
}
