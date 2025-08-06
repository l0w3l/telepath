<?php

namespace Lowel\Telepath;

use Illuminate\Support\Facades\Route;
use Lowel\Telepath\Commands\Hook\RemoveCommand;
use Lowel\Telepath\Commands\Hook\SetCommand;
use Lowel\Telepath\Commands\MakeHandlerCommand;
use Lowel\Telepath\Commands\MakeMiddlewareCommand;
use Lowel\Telepath\Commands\RunCommand;
use Lowel\Telepath\Core\Router\TelegramRouter;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
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
                MakeHandlerCommand::class,
                MakeMiddlewareCommand::class,
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

        $this->app->singleton(TelegramRouterInterface::class, function ($app) {
            return $app->make(TelegramRouter::class);
        });

        $this->app->resolving(TelegramRouterInterface::class, function (TelegramRouter $telegramRouter) {
            return $telegramRouter->resetState();
        });

        $this->app->singleton(TelegramRouterResolverInterface::class, function ($app) {
            return $app->make(TelegramRouterInterface::class);
        });

        $this->app->bind(TelegramAppFactoryInterface::class, function ($app) {
            return new TelegramAppFactory(
                $app->make(TelegramBotApi::class),
                $app->make(TelegramRouterResolverInterface::class)
            );
        });

        $this->loadRoutes();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    private function loadRoutes(): void
    {
        Route::middleware('api')->get('/api/webhook', function () {
            app(TelegramAppFactoryInterface::class)
                ->webhook()->start();
        });

        if (file_exists(config('telepath.routes'))) {
            (function () {
                require_once config('telepath.routes');
            })();
        }
    }
}
