<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardFactoryInterface;
use Lowel\Telepath\Core\Router\Keyboard\ReplyKeyboardBuilder;

readonly class CreateReplyTelegramKeyboardFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator($this->className, $this->namespace."\\$this->argumentName");

        $classGenerator
            ->setUse($this->namespace."\\$this->argumentName\\Buttons\\ExampleReplyButton")
            ->setUse(KeyboardBuilderInterface::class)
            ->setUse(ReplyKeyboardBuilder::class)
            ->setImplements(KeyboardFactoryInterface::class)
            ->setFunction("function make(): KeyboardBuilderInterface\n{\n{$classGenerator->spaces}\$builder = new ReplyKeyboardBuilder;\n\n{$classGenerator->spaces}return \$builder->row(new ExampleReplyButton());\n}");

        $this->createDirectoryIfNotExists();

        $this->saveInDirectoryName($classGenerator->generate());

        return $this->classPath;
    }
}
