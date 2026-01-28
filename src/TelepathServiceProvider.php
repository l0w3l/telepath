<?php

namespace Lowel\Telepath;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Lowel\Telepath\Commands\Conversation\MakeConversationCommand;
use Lowel\Telepath\Commands\Handler\MakeHandlerCommand;
use Lowel\Telepath\Commands\Hook\RemoveCommand;
use Lowel\Telepath\Commands\Hook\SetCommand;
use Lowel\Telepath\Commands\Hook\StatusCommand;
use Lowel\Telepath\Commands\Keyboard\MakeKeyboardInlineCommand;
use Lowel\Telepath\Commands\Keyboard\MakeKeyboardReplyCommand;
use Lowel\Telepath\Commands\MIddleware\MakeMiddlewareCommand;
use Lowel\Telepath\Commands\Router\RouteListCommand;
use Lowel\Telepath\Commands\RunCommand;
use Lowel\Telepath\Components\Benchmark\Benchmark;
use Lowel\Telepath\Components\Context\Context;
use Lowel\Telepath\Components\ExceptionHandler\ExceptionHandler;
use Lowel\Telepath\Core\Components\ComponentInterface;
use Lowel\Telepath\Core\Components\ComponentRegistratorInterface;
use Lowel\Telepath\Core\Components\ComponentsBundle;
use Lowel\Telepath\Core\Router\TelegramRouter;
use Lowel\Telepath\Core\Router\TelegramRouterInterface;
use Lowel\Telepath\Core\Router\TelegramRouterResolverInterface;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\Paranormal;
use Lowel\Telepath\Jobs\HandleTelegramUpdateRequestJob;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\InputFile;
use Phptg\BotApi\Type\Update\Update;
use Psr\Http\Message\ServerRequestInterface;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Throwable;

class TelepathServiceProvider extends PackageServiceProvider
{
    /**
     * @var class-string<ComponentInterface&ComponentRegistratorInterface>[]
     */
    private array $components = [
        Benchmark::class,
        Context::class,
        ExceptionHandler::class,
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
            ->hasMigrations([
                'create_telepath_stored_updates_table',
            ])
            ->hasRoute('telegram')
            ->hasCommands([
                RunCommand::class,
                SetCommand::class,
                RemoveCommand::class,
                MakeHandlerCommand::class,
                MakeMiddlewareCommand::class,
                MakeKeyboardInlineCommand::class,
                MakeKeyboardReplyCommand::class,
                MakeConversationCommand::class,
                StatusCommand::class,
                RouteListCommand::class,
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

        $this->bindComponents();

        $this->bindApp();

        if (Extrasense::profile()->chatIdFallback !== null && in_array(ExceptionHandler::class, $this->components)) {
            $this->addReportFallbackInTheChat();
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    private function bindComponents(): void
    {
        foreach ($this->components as $component) {
            $component::register($this->app);
        }

        $this->app->singleton(ComponentsBundle::class, function ($app) {
            $componentBundle = new ComponentsBundle;

            foreach ($this->components as $component) {
                if ($component::isRegistered()) {
                    $componentBundle->append(
                        $app->make($component)
                    );
                }
            }

            return $componentBundle;
        });
    }

    private function bindApp(): void
    {
        $this->app->singleton(TelegramRouterInterface::class, function ($app) {
            return $app->make(TelegramRouter::class);
        });

        $this->app->resolving(TelegramRouterInterface::class, function (TelegramRouter $telegramRouter) {
            return $telegramRouter->resetState();
        });

        $this->app->singleton(TelegramRouterResolverInterface::class, function ($app) {
            return $app->make(TelegramRouterInterface::class);
        });

        $this->app->bind(TelegramBotApi::class, function () {
            return new TelegramBotApi(
                token: Extrasense::profile()->token,
                baseUrl: config('telepath.base_uri'),
                logger: logger());
        });

        $this->app->bind(TelegramAppFactoryInterface::class, function ($app) {
            return new TelegramAppFactory(
                $app->make(TelegramBotApi::class),
                $app->make(TelegramRouterResolverInterface::class)
            );
        });

        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        Route::middleware('api')->post('/api/webhook', function () {
            $request = App::make(ServerRequestInterface::class);

            if (config('telepath.hook.async')) {
                HandleTelegramUpdateRequestJob::dispatch($request);
            } else {
                (new HandleTelegramUpdateRequestJob($request))->handle();
            }
        });

        if (file_exists(config('telepath.routes'))) {
            (function () {
                require_once config('telepath.routes');
            })();
        }
    }

    private function addReportFallbackInTheChat(): void
    {
        Paranormal::wrap(function (Throwable $e, Update $update, TelegramBotApi $api) {
            $cloner = new VarCloner;
            $dumper = new HtmlDumper;

            $stream = fopen('php://memory', 'r+');

            fwrite($stream, $dumper->dump($cloner->cloneVar($update), true));
            fwrite($stream, $dumper->dump($cloner->cloneVar($e), true));
            fwrite($stream, $dumper->dump($cloner->cloneVar(config('telepath')), true));

            rewind($stream);

            $api->sendDocument(Extrasense::profile()->chatIdFallback, new InputFile($stream, 'report_'.now()->format('Y-m-d_H-i-s').'.html'), caption: 'Report by '.now()->toString()."\n\nMessage: {$e->getMessage()}");

            fclose($stream);
        });
    }
}
