<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

/**
 * Interface of class generator
 */
interface ClassGeneratorInterface extends ObjectGeneratorInterface
{
    /**
     * Set extends of current class object
     *
     * @param  class-string  $classString
     */
    public function setExtends(string $classString): self;

    /**
     * Set implements of current class object
     *
     * @param  class-string  $interfaceString
     */
    public function setImplements(string $interfaceString): self;
}
