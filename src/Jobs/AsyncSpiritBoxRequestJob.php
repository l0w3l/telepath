<?php

declare(strict_types=1);

namespace Lowel\Telepath\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Lowel\Telepath\Enums\AsyncRequestEnum;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\FailResult;
use Phptg\BotApi\Type\Update\Update;
use Ramsey\Uuid\UuidInterface;

class AsyncSpiritBoxRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public UuidInterface $uuid,
        public Update $context,
        public string $methodName,
        public array $args,
    ) {
        $this->initRecord();
    }

    public function handle(): void
    {
        Extrasense::imaginate($this->context, function () {
            $result = forward_static_call_array([SpiritBox::class, $this->methodName], $this->args);

            $this->set($result);
        });
    }

    public static function create(Update $update, string $methodName, array $args): self
    {
        return new self(Str::uuid(), $update, $methodName, $args);
    }

    public function status(): AsyncRequestEnum
    {
        return Cache::get($this->key(), [])['status'] ?? AsyncRequestEnum::EXPIRED;
    }

    public function response(): mixed
    {
        return Cache::get($this->key(), [])['response'] ?? null;
    }

    public function delete(): void
    {
        Cache::delete($this->key());
    }

    private function initRecord(): void
    {
        Cache::set($this->key(), [
            'status' => AsyncRequestEnum::PENDING,
            'response' => null,
        ], now()->addHour());
    }

    private function set(mixed $response): void
    {
        Cache::set($this->key(), [
            'status' => $response instanceof FailResult ? AsyncRequestEnum::ERROR : AsyncRequestEnum::OK,
            'response' => $response,
        ], now()->addHour());
    }

    private function key(): string
    {
        return 'telepath.async.sb.'.$this->uuid;
    }
}
