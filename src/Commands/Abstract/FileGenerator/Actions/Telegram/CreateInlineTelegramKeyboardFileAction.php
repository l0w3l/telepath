<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\InlineKeyboardBuilder;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\KeyboardBuilderInterface;
use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\KeyboardFactoryInterface;

readonly class CreateInlineTelegramKeyboardFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator($this->className, $this->namespace."\\$this->argumentName");

        $classGenerator
            ->setUse($this->namespace."\\$this->argumentName\\Buttons\\ExampleInlineButton")
            ->setUse(KeyboardBuilderInterface::class)
            ->setUse(InlineKeyboardBuilder::class)
            ->setImplements(KeyboardFactoryInterface::class)
            ->setFunction("function make(): KeyboardBuilderInterface\n{\n{$classGenerator->spaces}\$builder = new InlineKeyboardBuilder;\n\n{$classGenerator->spaces}return \$builder->row(new ExampleInlineButton());\n}");

        $this->createDirectoryIfNotExists();

        if (! $this->filesystem->isDirectory($this->folderPath."{$this->argumentName}")) {
            $this->filesystem->makeDirectory($this->folderPath."{$this->argumentName}");
        }

        $this->save($this->folderPath."{$this->argumentName}/{$this->className}.php", $classGenerator->generate());

        return $this->classPath;
    }
}
