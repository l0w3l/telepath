<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\Traits;

trait ParseArgumentTrait
{
    /**
     * @param  string  $argument  - relative namespace-like argument from artisan command
     */
    private function parsePrefixPathByArgument(string $argument): string
    {
        $exploded = $this->parseArgumentSegments($argument);

        if (count($exploded) === 1) {
            return '';
        } else {
            array_pop($exploded);

            return '/'.implode('/', $exploded);
        }
    }

    private function parsePrefixNamespaceByArgument(string $argument): string
    {
        return str_replace('/', '\\', $this->parsePrefixPathByArgument($argument));
    }

    private function parseNameByArgument(string $argument): string
    {
        $exploded = $this->parseArgumentSegments($argument);

        return array_pop($exploded);
    }

    /**
     * @return list<string>
     */
    private function parseArgumentSegments(string $argument): array
    {
        if ($argument === '' || str_starts_with($argument, '/') || str_contains($argument, '\\')) {
            throw new \InvalidArgumentException('Generator name must be a relative class path.');
        }

        $segments = explode('/', $argument);

        foreach ($segments as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..' || preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $segment) !== 1) {
                throw new \InvalidArgumentException('Generator name contains an invalid path segment.');
            }
        }

        return $segments;
    }
}
