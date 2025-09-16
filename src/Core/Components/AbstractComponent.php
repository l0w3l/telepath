<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Throwable;
use Vjik\TelegramBot\Api\Type\Update\Update;

class AbstractComponent implements ComponentInterface, ComponentRegistratorInterface
{
    public function onCreated(): void
    {
        // TODO: Implement created() method.
    }

    public function onBefore(Update $update): void
    {
        // TODO: Implement before() method.
    }

    public function onError(Update $update, Throwable $e): void
    {
        // TODO: Implement onFailure() method.
    }

    public function onAfter(Update $update): void
    {
        // TODO: Implement after() method.
    }

    public function onDestroy(): void
    {
        // TODO: Implement destroy() method.
    }

    public static function register(Application $app): void
    {
        App::bind(static::class, fn ($app) => $app->make(static::class));
    }
}
