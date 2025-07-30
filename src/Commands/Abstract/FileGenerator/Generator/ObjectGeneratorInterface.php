<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

/**
 * Main file generator interface
 */
interface ObjectGeneratorInterface
{
    /**
     * Generate file by previous setup
     */
    public function generate(): string;

    /**
     * Set classname of current object
     */
    public function setClassname(string $className): static;

    /**
     * Set namespace of concrete object
     */
    public function setNamespace(string $namespace): static;

    /**
     * Set usage of concrete object
     *
     * @param  class-string  $className
     */
    public function setUse(string $className): static;

    /**
     * Set function of object
     */
    public function setFunction(string $template): static;
}
