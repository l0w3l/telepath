<?php

declare(strict_types=1);

namespace Lowel\Telepath\Config;

use Illuminate\Support\Str;
use Lowel\Telepath\Enums\ParseModeEnum;
use Lowel\Telepath\Enums\UpdateTypeEnum;

/**
 * @property string $token
 * @property string $username
 * @property int $offset
 * @property int $limit
 * @property int $timeout
 * @property string[] $allowedUpdates
 * @property string $parseMode
 * @property int[] $whitelist
 * @property int[] $blacklist
 * @property int|null $chatIdFallback
 * @property int $repeatAfterException
 * @property int $timeoutAfterException
 */
final class Profile
{
    private array $cache = [];

    public function __construct(
        public readonly string $profileName
    ) {}

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->cache)) {
            return $this->cache[$name];
        }

        $snakeName = Str::snake($name);
        $mutator = $this->mutator($snakeName);

        return $this->cache[$name] = $mutator(config("telepath.profiles.{$this->profileName}.{$snakeName}"));
    }

    private function mutator(string $name): callable
    {
        return match ($name) {
            'allowed_updates' => fn ($value) => UpdateTypeEnum::toArray(explode(',', $value)),
            'whitelist' => fn ($value) => array_map(fn ($x) => (int) $x, explode(',', $value)),
            'blacklist' => fn ($value) => array_map(fn ($x) => (int) $x, explode(',', $value)),
            'parse_mode' => fn ($value) => ParseModeEnum::from($value)->value,
            default => fn ($value) => $value,
        };
    }
}
