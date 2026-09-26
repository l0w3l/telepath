<?php

namespace Lowel\Telepath\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Lowel\Telepath\Core\Router\TelegramDispatcher;
use Lowel\Telepath\Core\Router\TelegramRouteRegistry;
use Lowel\Telepath\TelepathServiceProvider;
use Lowel\Telepath\Tests\Mock\Support\TelegramUpdatesMock;
use Orchestra\Testbench\TestCase as Orchestra;
use Phptg\BotApi\Type\Update\Update;

class TestCase extends Orchestra
{
    public TelegramUpdatesMock $updatesMockBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updatesMockBuilder = new TelegramUpdatesMock;

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Lowel\\Telepath\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function withinTelegramRoutes(callable $routes): void
    {
        Route::setRoutes(new RouteCollection);
        app(TelegramRouteRegistry::class)->clear();

        $routes();
    }

    protected function dispatchTelegramUpdates(): void
    {
        config()->set('telepath.get_updates', true);

        foreach ($this->updatesMockBuilder->getUpdates() as $update) {
            $this->dispatchTelegramUpdate($update);
        }
    }

    protected function dispatchTelegramUpdate(Update $update): void
    {
        app(TelegramDispatcher::class)->dispatch($update);
    }

    protected function getPackageProviders($app): array
    {
        return [
            TelepathServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('telepath', require __DIR__.'/../config/telepath.php');
        config()->set('telepath.profiles.default.token', 'TEST_BOT');

        foreach (File::allFiles(__DIR__.'/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
        }
    }
}
