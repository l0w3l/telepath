<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

class InterfaceGenerator extends AbstractObjectGenerator implements InterfaceGeneratorInterface
{
    public function __construct(string $className, string $namespace)
    {
        $className = "interface $className";
        parent::__construct($className, $namespace);
    }

    public function setExtends(string $interfaceString): InterfaceGeneratorInterface
    {
        $interfaceParts = explode('\\', $interfaceString);

        $this->setPart(ObjectPartsEnum::USE, $interfaceString);
        $this->setPart(ObjectPartsEnum::EXTENDS, $interfaceParts[array_key_last($interfaceParts)]);

        return $this;
    }
}
