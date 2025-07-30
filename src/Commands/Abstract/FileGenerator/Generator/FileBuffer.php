<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

use Illuminate\Filesystem\Filesystem;
use Lowel\Telepath\Exceptions\Commands\FileDuplicatedException;

readonly class FileBuffer
{
    public function __construct(
        public Filesystem $filesystem,
        public string $buffer,
    ) {}

    /**
     * @throws FileDuplicatedException
     */
    public function save(string $path): void
    {
        if ($this->filesystem->isFile($path)) {
            throw new FileDuplicatedException($path);
        }

        $this->filesystem->put($path, $this->buffer);
    }
}
