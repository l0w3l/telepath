<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards;

use Closure;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Attributes\InlineButtonAttribute;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonHandler;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Lowel\Telepath\Traits\HashAbleTrait;
use ReflectionClass;
use ReflectionMethod;
use Vjik\TelegramBot\Api\Type\InlineKeyboardButton;
use Vjik\TelegramBot\Api\Type\InlineKeyboardMarkup;

/**
 * @implements KeyboardInterface<InlineKeyboardMarkup>
 */
abstract class AbstractInlineKeyboard implements KeyboardInterface
{
    use HashAbleTrait;

    /**
     * @return array<array{
     *     name: string,
     *     meta?: InlineButtonAttribute
     * }>
     */
    public static function getButtonMethods(): array
    {
        $reflection = new ReflectionClass(static::class);

        $methodsInfo = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // only child methods
            if ($method->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            // only methods with '*Button' postfix
            if (! str_ends_with($method->getName(), 'Button')) {
                continue;
            }

            // collect attributes
            $meta = null;
            foreach ($method->getAttributes() as $attr) {
                $instance = $attr->newInstance();

                if ($instance instanceof InlineButtonAttribute) {
                    $meta = $instance;
                }

            }

            $methodsInfo[] = [
                'name' => $method->getName(),
                'meta' => $meta,
            ];
        }

        return $methodsInfo;
    }

    public static function build(): InlineKeyboardMarkup
    {
        $methods = self::getButtonMethods();

        $buttons = [[]];
        foreach ($methods as $method) {
            $meta = $method['meta'] ?? new InlineButtonAttribute($method['name']);

            $callbackData = self::getCallbackData($meta, $method['name']);

            $inlineButton = new InlineKeyboardButton(
                $meta->text,
                $meta->url,
                $callbackData,
                $meta->webApp,
                $meta->loginUrl,
                $meta->switchInlineQuery,
                $meta->switchInlineQueryCurrentChat,
                $meta->switchInlineQueryChosenChat,
                $meta->callbackGame,
                $meta->pay,
                $meta->copyText
            );

            if ($meta->direction === 'row') {
                $buttons[array_key_last($buttons)][] = $inlineButton;
            } elseif ($meta->direction === 'col') {
                $buttons[][] = $inlineButton;
            }
        }

        return new InlineKeyboardMarkup($buttons);
    }

    public static function handlers(): array
    {
        $handlers = [];

        foreach (static::getButtonMethods() as $method) {
            $instance = App::make(static::class);
            $meta = $method['meta'] ?? new InlineButtonAttribute($method['name']);

            $handlers[] = new ButtonHandler(
                Closure::fromCallable([$instance, $method['name']]),
                self::getCallbackData($meta, $method['name']),
                UpdateTypeEnum::CALLBACK_QUERY
            );
        }

        return $handlers;
    }

    protected static function getCallbackData(mixed $meta, $name): ?string
    {
        return match ($meta->copyText || $meta->switchInlineQuery || $meta->switchInlineQueryCurrentChat || $meta->webApp || $meta->loginUrl || $meta->url) {
            true => null,
            false => $meta->callbackData ?? (self::shortHash(static::class).':'.self::shortHash($name)),
        };
    }
}
