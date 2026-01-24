<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Core\Router\Handler\AbstractTelegramHandler;
use Lowel\Telepath\Facades\SpiritBox;

readonly class CreateTelegramHandlerFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator($this->className, $this->namespace);

        $classGenerator
            ->setUse(SpiritBox::class)
            ->setExtends(AbstractTelegramHandler::class)
            ->setFunction("function handler(): callable\n{\n{$classGenerator->spaces}return static function() {\n{$classGenerator->spaces}{$classGenerator->spaces}SpiritBox::sendMessage('example text');\n{$classGenerator->spaces}};\n}");

        $this->createDirectoryIfNotExists();

        $this->save($this->classPath, $classGenerator->generate());

        return $this->classPath;
    }
}
