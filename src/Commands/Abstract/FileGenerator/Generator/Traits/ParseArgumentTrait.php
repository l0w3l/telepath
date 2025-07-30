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
        $exploded = explode('/', $argument);

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
        $exploded = explode('/', $argument);

        return array_pop($exploded);
    }
}
