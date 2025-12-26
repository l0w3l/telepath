<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\Benchmark;

use DateTimeImmutable;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Foundation\Application;
use Lowel\Telepath\Core\Components\AbstractComponent;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Vjik\TelegramBot\Api\Type\Update\Update;

class Benchmark extends AbstractComponent implements BenchmarkInterface
{
    use InteractsWithIO;

    private DateTimeImmutable $getUpdateTrigger;

    private DateTimeImmutable $updateExecutionTrigger;

    private array $journal = [];

    public function __construct()
    {
        $this->getUpdateTrigger = new DateTimeImmutable;
        $this->updateExecutionTrigger = new DateTimeImmutable;
        $this->output = new OutputStyle(new StringInput(''), new ConsoleOutput);
    }

    public static function register(Application $app): void
    {
        $app->singleton(Benchmark::class, fn () => new self);
        $app->singleton(BenchmarkInterface::class, fn ($app) => $app->make(Benchmark::class));
    }

    public static function isRegistered(): bool
    {
        return config('app.debug') && (env('TELEPATH_TESTING', false) === false);
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
            'execution' => (float) $now->diff($this->updateExecutionTrigger)->format('%s.%f') * 1000,
        ];
    }

    public function onDestroy(): void
    {
        $now = new DateTimeImmutable;

        $this->journal[] = [
            'update_id' => 'total',
            'execution' => (float) $now->diff($this->getUpdateTrigger)->format('%s.%f') * 1000,
        ];

        $this->table([
            'update_id',
            'execution_duration_ms',
        ], $this->journal);

        $this->journal = [];
    }

    public function getJournal(): array
    {
        return $this->journal;
    }
}
