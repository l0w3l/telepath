<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher;

use Illuminate\Contracts\Foundation\Application;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\ButtonHandler;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\KeyboardInterface;
use Lowel\Telepath\Core\Components\AbstractComponent;
use Lowel\Telepath\Facades\Telepath;
use Lowel\Telepath\Traits\HashAbleTrait;
use Lowel\Telepath\Traits\InvokeAbleTrait;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

class KeyboardsWatcher extends AbstractComponent implements KeyboardsWatcherInterface
{
    use HashAbleTrait, InvokeAbleTrait;

    /**
     * @var class-string<KeyboardInterface>[]
     */
    protected array $keyboards = [];

    public static function register(Application $app): void
    {
        $app->singleton(KeyboardsWatcher::class, fn ($app) => new self);
        $app->singleton(KeyboardsWatcherInterface::class, fn ($app) => $app->make(KeyboardsWatcher::class));
    }

    public function watch(string ...$keyboards): KeyboardsWatcherInterface
    {
        /** @var class-string<KeyboardInterface> $keyboard */
        foreach ($keyboards as $keyboard) {
            if (! in_array($keyboard, $this->keyboards)) {
                foreach ($keyboard::handlers() as $handler) {
                    $this->bindViaTelepath($handler);
                }

                $this->keyboards[] = $keyboard;
            }
        }

        return $this;
    }

    private function bindViaTelepath(ButtonHandler $handler): void
    {
        Telepath::on($handler->callback, $handler->updateType, $handler->pattern)
            ->middleware(function (TelegramBotApi $bot, Update $update, callable $next) {
                $next();

                $bot->answerCallbackQuery($update->callbackQuery->id);
            });
    }
}
