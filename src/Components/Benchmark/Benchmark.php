<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\Benchmark;

use DateTimeImmutable;
use Illuminate\Contracts\Foundation\Application;
use Lowel\Telepath\Core\Components\AbstractComponent;
use Vjik\TelegramBot\Api\Type\Update\Update;

class Benchmark extends AbstractComponent implements BenchmarkInterface
{
    private DateTimeImmutable $getUpdateTrigger;

    private DateTimeImmutable $updateExecutionTrigger;

    private array $journal = [];

    public function __construct()
    {
        $this->getUpdateTrigger = new DateTimeImmutable;
        $this->updateExecutionTrigger = new DateTimeImmutable;
    }

    public static function register(Application $app): void
    {
        $app->singleton(Benchmark::class, fn () => new self);
        $app->singleton(BenchmarkInterface::class, fn ($app) => $app->make(Benchmark::class));
    }

    public function onCreated(): void
    {
        $this->getUpdateTrigger = new DateTimeImmutable;
    }

    public function onBefore(Update $update): void
    {
        $this->updateExecutionTrigger = new DateTimeImmutable;
    }

    public function onAfter(Update $update): void
    {
        $now = new DateTimeImmutable;

        $this->journal[] = [
            'update_id' => $update->updateId,
            'update_execution_duration_ms' => (float) $now->diff($this->updateExecutionTrigger)->format('%s.%f') * 1000,
        ];
    }

    public function onDestroy(): void
    {
        $now = new DateTimeImmutable;

        $this->journal[] = [
            'get_update_duration_ms' => (float) $now->diff($this->getUpdateTrigger)->format('%s.%f') * 1000,
        ];

        if (config('app.debug')) {
            dump($this->getJournal());
        }

        $this->journal = [];
    }

    public function getJournal(): array
    {
        return $this->journal;
    }
}
