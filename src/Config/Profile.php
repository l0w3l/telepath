<?php

declare(strict_types=1);

namespace Lowel\Telepath\Config;

use Lowel\Telepath\Enums\UpdateTypeEnum;

final readonly class Profile
{
    /**
     * @param  string[]  $allowedUpdates
     * @param  int[]  $whitelist
     * @param  int[]  $blacklist
     */
    public function __construct(
        public string $token,
        public int $offset,
        public int $limit,
        public int $timeout,
        public array $allowedUpdates = [],
        public array $whitelist = [],
        public array $blacklist = [],
        public ?int $chatIdFallback = null,
    ) {}

    public static function fromArray(array $array): Profile
    {
        return new self(
            $array['token'] ?? '',
            $array['offset'],
            $array['limit'],
            $array['timeout'],
            UpdateTypeEnum::toArray($array['allowed_updates']),
            $array['whitelist'],
            $array['blacklist'],
            $array['chat_id_fallback'],
        );
    }
}
