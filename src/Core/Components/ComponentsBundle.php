<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Components;

use Illuminate\Contracts\Foundation\Application;
use Throwable;
use Vjik\TelegramBot\Api\Type\Update\Update;

class ComponentsBundle implements ComponentInterface
{
    /**
     * @var ComponentInterface[]
     */
    private array $components = [];

    public function append(ComponentInterface $component): self
    {
        $this->components[] = $component;

        return $this;
    }

    public function created(): void
    {
        foreach ($this->components as $component) {
            $component->created();
        }
    }

    public function before(Update $update): void
    {
        foreach ($this->components as $component) {
            $component->before($update);
        }
    }

    public function onSuccess(Update $update): void
    {
        foreach ($this->components as $component) {
            $component->onSuccess($update);
        }
    }

    public function onFailure(Update $update, Throwable $e): void
    {
        foreach ($this->components as $component) {
            $component->onFailure($update, $e);
        }
    }

    public function after(Update $update): void
    {
        foreach ($this->components as $component) {
            $component->after($update);
        }
    }

    public function destroy(): void
    {
        foreach ($this->components as $component) {
            $component->destroy();
        }
    }

    public static function register(Application $app): void
    {
        $app->singleton(ComponentInterface::class, fn () => new ComponentsBundle);
    }
}
