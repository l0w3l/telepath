<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Keyboard\Buttons\Reply;

use Illuminate\Routing\Route;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\AbstractButton;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\ButtonInterface;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Helpers\Invoker;
use Phptg\BotApi\Type\KeyboardButton;

abstract class AbstractReplyButton extends AbstractButton implements ButtonInterface
{
    public function toButton(): KeyboardButton
    {
        return new KeyboardButton(
            text: $this->getText(),
            iconCustomEmojiId: $this->getIconCustomEmojiId(),
            style: $this->getStyle()
        );
    }

    public function resolve(TelegramRouterInterface $telegramRouter): Route
    {
        $pattern = $this->resolvePattern();

        return $telegramRouter->onMessage([static::class, 'handle'], $pattern);
    }

    private function resolvePattern(): ?string
    {
        try {
            $ref = new \ReflectionMethod(static::class, 'pattern');

            return $ref->invoke(null);
        } catch (\ReflectionException $e) {
            return $this->getText();
        }
    }

    public static function trigger(): void
    {
        Invoker::call(static::make(), 'handle');
    }
}
