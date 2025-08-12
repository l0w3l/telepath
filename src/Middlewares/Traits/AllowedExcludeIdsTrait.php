<?php

declare(strict_types=1);

namespace Lowel\Telepath\Middlewares\Traits;

use Closure;

trait AllowedExcludeIdsTrait
{
    private null|Closure|array $allowedUserIds;

    private null|Closure|array $excludeUserIds;

    /**
     * @param  int[]  $default
     * @return int[] List of user IDs to allow from processing
     */
    public function getAllowedIds(?array $default = null): array
    {
        if ($this->allowedUserIds instanceof Closure) {
            return $this->allowedUserIds->call($this);
        } else {
            return $this->allowedUserIds ?? $default ?? [];
        }
    }

    /**
     * @param  int[]  $default
     * @return int[] List of user IDs to exclude from processing
     */
    public function getExcludeIds(?array $default = null): array
    {
        if ($this->excludeUserIds instanceof Closure) {
            return $this->excludeUserIds->call($this);
        } else {
            return $this->excludeUserIds ?? $default ?? [];
        }
    }
}
