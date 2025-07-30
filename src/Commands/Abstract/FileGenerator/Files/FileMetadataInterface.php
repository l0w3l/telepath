<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Files;

interface FileMetadataInterface
{
    public function convertInClassName(string $argumentName): string;

    public function getPath(): string;

    public function getNamespace(): string;
}
