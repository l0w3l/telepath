<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Throwable;
use Vjik\TelegramBot\Api\Type\Update\Update;

class AbstractComponent implements ComponentInterface, ComponentRegistratorInterface
{
    public function created(): void
    {
        // TODO: Implement created() method.
    }

    public function before(Update $update): void
    {
        // TODO: Implement before() method.
    }

    public function onSuccess(Update $update): void
    {
        // TODO: Implement onSuccess() method.
    }

    public function onFailure(Update $update, Throwable $e): void
    {
        // TODO: Implement onFailure() method.
    }

    public function after(Update $update): void
    {
        // TODO: Implement after() method.
    }

    public function destroy(): void
    {
        // TODO: Implement destroy() method.
    }

    public static function register(Application $app): void
    {
        App::bind(static::class, fn ($app) => $app->make(static::class));
    }
}
