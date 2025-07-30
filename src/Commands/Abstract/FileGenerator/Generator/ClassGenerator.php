<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

class ClassGenerator extends AbstractObjectGenerator implements ClassGeneratorInterface
{
    public function __construct(string $className, string $namespace)
    {
        $className = "class {$className}";

        parent::__construct($className, $namespace);
    }

    public function setExtends(string $classString): ClassGeneratorInterface
    {
        $classNameParts = explode('\\', $classString);

        $this->setPart(ObjectPartsEnum::USE, $classString);
        $this->setPart(ObjectPartsEnum::EXTENDS, $classNameParts[array_key_last($classNameParts)]);

        return $this;
    }

    public function setImplements(string $interfaceString): ClassGeneratorInterface
    {
        $classNameParts = explode('\\', $interfaceString);

        $this->setPart(ObjectPartsEnum::USE, $interfaceString);
        $this->setPart(ObjectPartsEnum::IMPLEMENTS, $classNameParts[array_key_last($classNameParts)]);

        return $this;
    }
}
