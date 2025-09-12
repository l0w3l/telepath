<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\ExceptionHandler;

use Illuminate\Contracts\Foundation\Application;
use Lowel\Telepath\Core\Components\AbstractComponent;
use Lowel\Telepath\Core\Traits\InvokeAbleTrait;
use Throwable;
use Vjik\TelegramBot\Api\Type\Update\Update;

class ExceptionHandler extends AbstractComponent implements ExceptionHandlerInterface
{
    use InvokeAbleTrait;

    /** @var callable[] */
    private array $stack = [];

    public static function register(Application $app): void
    {
        $app->singleton(ExceptionHandler::class, fn ($app) => new self);
        $app->singleton(ExceptionHandlerInterface::class, fn ($app) => $app->make(ExceptionHandler::class));
    }

    public function onFailure(Update $update, Throwable $e): void
    {
        $this->catch($update, $e);
    }

    public function wrap(mixed $callback): void
    {
        $this->stack[] = $callback;
    }

    public function catch(Update $update, Throwable $e): void
    {
        $returnValue = null;

        foreach ($this->stack as $handler) {
            $returnValue = $this->invokeStaticClassWithArgs($handler, compact('update', 'e', 'returnValue'));
        }
    }

    public function reset(): void
    {
        $this->stack = [];
    }
}
