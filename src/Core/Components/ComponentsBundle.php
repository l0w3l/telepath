<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Components;

use Illuminate\Contracts\Foundation\Application;
use Phptg\BotApi\Type\Update\Update;
use Throwable;

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

    public function onCreated(): void
    {
        foreach ($this->components as $component) {
            $component->onCreated();
        }
    }

    public function onBefore(Update $update): void
    {
        foreach ($this->components as $component) {
            $component->onBefore($update);
        }
    }

    public function onError(Update $update, Throwable $e): void
    {
        foreach ($this->components as $component) {
            $component->onError($update, $e);
        }
    }

    public function onAfter(Update $update): void
    {
        foreach ($this->components as $component) {
            $component->onAfter($update);
        }
    }

    public function onDestroy(): void
    {
        foreach ($this->components as $component) {
            $component->onDestroy();
        }
    }

    public static function register(Application $app): void
    {
        $app->singleton(ComponentInterface::class, fn () => new ComponentsBundle);
    }
}
