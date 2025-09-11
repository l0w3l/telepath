<?php

declare(strict_types=1);

namespace Lowel\Telepath\Config;

use Lowel\Telepath\Enums\UpdateTypeEnum;

final readonly class Profile
{
    /**
     * @param  string[]  $allowedUpdates
     * @param  array<int|string>  $whitelist
     * @param  array<int|string>  $blacklist
     */
    public function __construct(
        public int $offset,
        public int $limit,
        public int $timeout,
        public array $allowedUpdates = [],
        public array $whitelist = [],
        public array $blacklist = [],
    ) {}

    public static function fromArray(array $array): Profile
    {
        return new self(
            $array['offset'],
            $array['limit'],
            $array['timeout'],
            UpdateTypeEnum::toArray($array['allowed_updates']),
            $array['whitelist'],
            $array['blacklist'],
        );
    }
}
