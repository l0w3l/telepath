<?php

declare(strict_types=1);

namespace Lowel\Telepath;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Lowel\Telepath\Commands\Handler\MakeHandlerCommand;
use Lowel\Telepath\Commands\Hook\KeyGenerateCommand;
use Lowel\Telepath\Commands\Hook\RemoveCommand;
use Lowel\Telepath\Commands\Hook\SetCommand;
use Lowel\Telepath\Commands\Hook\StatusCommand;
use Lowel\Telepath\Commands\Keyboard\MakeKeyboardInlineCommand;
use Lowel\Telepath\Commands\Keyboard\MakeKeyboardReplyCommand;
use Lowel\Telepath\Commands\MIddleware\MakeMiddlewareCommand;
use Lowel\Telepath\Commands\RunCommand;
use Lowel\Telepath\Components\Context\Context;
use Lowel\Telepath\Core\Router\TelegramDispatcher;
use Lowel\Telepath\Core\Router\TelegramRouteFactory;
use Lowel\Telepath\Core\Router\TelegramRouteMatcher;
use Lowel\Telepath\Core\Router\TelegramRouter;
use Lowel\Telepath\Core\Router\TelegramRouteRegistry;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Http\Guards\TelegramGuard;
use Lowel\Telepath\Http\Middlewares\Authorization\TelegramOriginMiddleware;
use Lowel\Telepath\Http\Middlewares\ErrorHandlers\ErrorReportMiddleware;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TelepathServiceProvider extends PackageServiceProvider
{
    const array DEFAULT_MIDDLEWARES = [
        TelegramOriginMiddleware::class,
        ErrorReportMiddleware::class,
    ];

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
            ->hasMigration('create_tg_users_table')
            ->hasCommands([
                RunCommand::class,
                SetCommand::class,
                RemoveCommand::class,
                MakeHandlerCommand::class,
                MakeMiddlewareCommand::class,
                MakeKeyboardInlineCommand::class,
                MakeKeyboardReplyCommand::class,
                StatusCommand::class,
                KeyGenerateCommand::class,
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

        $this->bindApp();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();

        Auth::extend('telegram', function ($app, $name, array $config) {
            return new TelegramGuard(Extrasense::update());
        });
    }

    private function bindApp(): void
    {
        $this->app->singleton(TelegramRouteRegistry::class);
        $this->app->singleton(TelegramRouteFactory::class);
        $this->app->singleton(TelegramRouteMatcher::class);
        $this->app->singleton(TelegramDispatcher::class);

        $this->app->singleton(TelegramRouterInterface::class, function ($app) {
            return $app->make(TelegramRouter::class);
        });

        $this->app->bind(TelegramBotApi::class, function () {
            return new TelegramBotApi(
                token: Extrasense::profile()->token,
                baseUrl: config('telepath.base_uri'),
                logger: logger());
        });

        Context::register($this->app);

        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        Route::middleware(TelegramOriginMiddleware::class)->prefix('/telepath/')->group(function () {

            Route::post('/webhook', function () {
                $request = request();

                $update = Update::fromJson($request->getContent());

                return app(TelegramDispatcher::class)->dispatch($update);
            });

            app(TelegramRouterInterface::class)->group(['middleware' => [
                ErrorReportMiddleware::class,
            ]], function () {
                require config('telepath.routes');
            });

            Route::any('/{any}', function () {
                return response(status: 200);
            })->where('any', '.*');
        });
    }
}
