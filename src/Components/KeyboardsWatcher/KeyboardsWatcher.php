<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\KeyboardFactoryInterface;
use Lowel\Telepath\Core\Components\AbstractComponent;
use Lowel\Telepath\Facades\Telepath;
use Vjik\TelegramBot\Api\TelegramBotApi;
use Vjik\TelegramBot\Api\Type\Update\Update;

class KeyboardsWatcher extends AbstractComponent implements KeyboardsWatcherInterface
{
    public static function register(Application $app): void
    {
        $app->singleton(KeyboardsWatcher::class, fn ($app) => new self);
        $app->singleton(KeyboardsWatcherInterface::class, fn ($app) => $app->make(KeyboardsWatcher::class));
    }

    public function watch(string ...$keyboards): KeyboardsWatcherInterface
    {
        foreach ($keyboards as $keyboard) {
            $keyboardFactoryInstance = App::make($keyboard);

            if (! ($keyboardFactoryInstance instanceof KeyboardFactoryInterface)) {
                throw new \RuntimeException('KeyboardWatcher accept only KeyboardFactoryInterface instances as a keyboard');
            }

            $markup = $keyboardFactoryInstance->make()->toArray();

            foreach ($markup as $column) {
                foreach ($column as $button) {
                    if ($button instanceof AbstractCallbackButton) {
                        Telepath::onCallbackQuery($button->handle()(...), "/^{$button->callbackDataId()}.*$/")
                            ->middleware(function (TelegramBotApi $api, Update $update, callable $next) {
                                $next();
                                $api->answerCallbackQuery($update->callbackQuery->id);
                            });
                    }
                }

            }
        }

        return $this;
    }
}
