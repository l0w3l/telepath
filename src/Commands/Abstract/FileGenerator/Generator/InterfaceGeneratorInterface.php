<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

/**
 * Interface of interface file generator
 */
interface InterfaceGeneratorInterface extends ObjectGeneratorInterface
{
    /**
     * Set extends of current interface
     *
     * @param  class-string  $interfaceString
     */
    public function setExtends(string $interfaceString): self;
}
