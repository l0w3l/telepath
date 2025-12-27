<?php

namespace Lowel\Telepath\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Lowel\Telepath\TelegramAppFactoryInterface;
use Lowel\Telepath\TelepathServiceProvider;
use Lowel\Telepath\Tests\Mock\Support\TelegramUpdatesMock;
use Lowel\Telepath\Tests\Mock\TelegramAppFactoryMock;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public TelegramUpdatesMock $updatesMockBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(TelegramAppFactoryInterface::class, fn ($app) => $this->app->make(TelegramAppFactoryMock::class));

        $this->updatesMockBuilder = new TelegramUpdatesMock;

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Lowel\\Telepath\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
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
