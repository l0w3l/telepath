<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\Telegram;

use Lowel\Telepath\Commands\Abstract\FileGenerator\Actions\AbstractCreateFileAction;
use Lowel\Telepath\Commands\Abstract\FileGenerator\Generator\ClassGenerator;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\Update\Update;

readonly class CreateTelegramHandlerFileAction extends AbstractCreateFileAction
{
    public function create(): string
    {
        $classGenerator = new ClassGenerator($this->className, $this->namespace);

        $classGenerator
            ->setUse(SpiritBox::class)
            ->setUse(Update::class)
            ->setFunction("/**\n * Handle Telegram update\n */\nfunction start(Update \$update)\n{\n{$classGenerator->spaces}SpiritBox::sendMessage('start text');\n}");

        $this->createDirectoryIfNotExists();

        $this->save($this->classPath, $classGenerator->generate());

        return $this->classPath;
    }
}
