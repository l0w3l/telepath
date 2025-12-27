<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Keyboard\InlineKeyboardBuilder;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardFactoryInterface;

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

        $this->saveInDirectoryName($classGenerator->generate());

        return $this->classPath;
    }
}
