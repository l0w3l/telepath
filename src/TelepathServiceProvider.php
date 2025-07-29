<?php

namespace Lowel\Telepath;

use Illuminate\Support\Facades\Route;
use Lowel\Telepath\Commands\Hook\RemoveCommand;
use Lowel\Telepath\Commands\Hook\SetCommand;
use Lowel\Telepath\Commands\RunCommand;
use Lowel\Telepath\Core\Router\Handler\TelegramHandlerCollectionInterface;
use Lowel\Telepath\Core\Router\TelegramRouter;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vjik\TelegramBot\Api\TelegramBotApi;

class TelepathServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('telepath')
            ->hasConfigFile()
            ->hasRoute('telegram')
            ->hasCommands([
                RunCommand::class,
                SetCommand::class,
                RemoveCommand::class,
            ]);
    }

    /**
     * Register services.
     *
     * @throws InvalidPackage
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(TelegramBotApi::class, function () {
            return new TelegramBotApi(
                token: config('telepath.token'),
                baseUrl: config('telepath.base_uri'),
                logger: logger());
        });

        $this->app->bind(TelegramAppFactoryInterface::class, function ($app) {
            return new TelegramAppFactory(
                $app->make(TelegramBotApi::class),
                $app->make(TelegramHandlerCollectionInterface::class)
            );
        });

        $this->app->singleton(TelegramRouterInterface::class, function ($app) {
            return new TelegramRouter;
        });

        $this->app->singleton(TelegramHandlerCollectionInterface::class, function ($app) {
            return $app->make(TelegramRouterInterface::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();

        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        Route::middleware('api')->get('/webhook', function () {
            $telegramAppFactory = app(TelegramAppFactoryInterface::class);

            $telegramAppFactory->webhook()
                ->start();
        });

        if (file_exists(config('telegram.routes'))) {
            (function () {
                require_once config('telegram.routes');
            })();
        }
    }
}
