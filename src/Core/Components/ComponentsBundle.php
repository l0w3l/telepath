<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Lowel\Telepath\Facades\Extrasense;
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

    /**
     * @throws Throwable
     */
    public function onError(Update $update, Throwable $e): void
    {
        $profile = Extrasense::profile();

        /** @var array{times: int} $repeated */
        $repeated = Cache::remember('telegram.exceptions.update_'.$update->updateId, $profile->repeatAfterException * $profile->timeoutAfterException * 10, fn () => [
            'times' => $profile->repeatAfterException - 1,
        ]);

        foreach ($this->components as $component) {
            $component->onError($update, $e);
        }

        Cache::decrement('telegram.exceptions.update_'.$update->updateId.'.times');

        if ($repeated['times'] > 0) {
            sleep($profile->timeoutAfterException);
            throw new $e;
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
