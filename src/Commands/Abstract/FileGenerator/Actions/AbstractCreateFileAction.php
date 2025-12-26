<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Files\FileMetadataInterface;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\Traits\ParseArgumentTrait;
use Lowel\Telepath\Exceptions\Commands\FileDuplicatedException;

abstract readonly class AbstractCreateFileAction
{
    use ParseArgumentTrait;

    protected Filesystem $filesystem;

    /** @var string Argument end-name like Test2 in Test1/Test2 */
    public string $argumentName;

    /** @var string Class name from StubInterface::convertInClassName conversion */
    public string $className;

    /** @var string Full class namespace */
    public string $namespace;

    /** @var string Full folder path */
    public string $folderPath;

    /** @var string Full class path */
    public string $classPath;

    /**
     * @param  string  $argument  Raw argument like Test, Test1/Test2, Test1/Test2/Test3 etc.
     */
    public function __construct(
        public FileMetadataInterface $stub,
        public string $argument,
    ) {
        $this->filesystem = new Filesystem;

        $this->argumentName = $this->parseNameByArgument($argument);
        $this->className = $this->stub->convertInClassName($this->argumentName);
        $this->namespace = Str::deduplicate("{$this->stub->getNamespace()}{$this->parsePrefixNamespaceByArgument($argument)}", '\\');
        $this->folderPath = Str::deduplicate("{$this->stub->getPath()}/{$this->parsePrefixPathByArgument($argument)}", '/');
        $this->classPath = Str::deduplicate($this->folderPath."/{$this->className}.php", '/');
    }

    /**
     * @return string file destination
     *
     * @throws FileDuplicatedException
     */
    abstract public function create(): string;

    protected function createDirectoryIfNotExists(): void
    {
        if (! $this->filesystem->isDirectory($this->folderPath)) {
            $this->filesystem->makeDirectory($this->folderPath, recursive: true);
        }
    }

    /**
     * @throws FileDuplicatedException
     */
    protected function save(string $path, string $fileContent): void
    {
        if ($this->filesystem->isFile($path)) {
            throw new FileDuplicatedException($path);
        }

        $this->filesystem->put($path, $fileContent);
    }

    protected function saveInDirectoryName(string $content): void
    {
        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}");
        }

        $this->save($this->folderPath."{$this->argumentName}/{$this->className}.php", $content);
    }
}
