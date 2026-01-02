<?php

declare(strict_types=1);

namespace Lowel\Telepath\Config;

use Illuminate\Support\Str;
use Lowel\Telepath\Enums\UpdateTypeEnum;

/**
 * @property string $token
 * @property string $username
 * @property int $offset
 * @property int $limit
 * @property int $timeout
 * @property string[] $allowedUpdates
 * @property int[] $whitelist
 * @property int[] $blacklist
 * @property int|null $chatIdFallback
 */
final readonly class Profile
{
    public function __construct(
        public string $profileName
    ) {}

    public function __get(string $name)
    {
        $snakeName = Str::snake($name);
        $mutator = $this->mutator($snakeName);

        return $mutator(config("telepath.profiles.{$this->profileName}.{$snakeName}"));
    }

    private function mutator(string $name): callable
    {
        return match ($name) {
            'allowed_updates' => fn ($value) => UpdateTypeEnum::toArray(explode(',', $value)),
            'whitelist' => fn ($value) => array_map(fn ($x) => (int) $x, explode(',', $value)),
            'blacklist' => fn ($value) => array_map(fn ($x) => (int) $x, explode(',', $value)),
            default => fn ($value) => $value,
        };
    }
}
