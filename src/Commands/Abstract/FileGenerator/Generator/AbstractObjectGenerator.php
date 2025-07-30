<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

use Exception;

abstract class AbstractObjectGenerator implements ObjectGeneratorInterface
{
    /**
     * @var array<ObjectPartsEnum, string[]>
     */
    private array $parts;

    public readonly string $spaces;

    public function __construct(
        public readonly string $className,
        public readonly string $namespace,
    ) {
        $this->parts = [];
        $this->spaces = "\t";

        $this->setClassname($className);
        $this->setNamespace($namespace);
    }

    /**
     * @throws Exception
     */
    public function generate(): string
    {
        $file = '';

        // header
        $file .= "<?php\n\ndeclare(strict_types=1);\n\n";

        $file .= 'namespace '.$this->getPart(ObjectPartsEnum::NAMESPACE)[0].";\n\n";

        // use ...;
        $file .= implode('', array_map(fn ($value) => "use {$value};\n", $this->getPart(ObjectPartsEnum::USE)))."\n";

        // classname
        $file .= $this->getPart(ObjectPartsEnum::CLASSNAME)[0];

        try {
            // extends
            $file .= ' extends '.implode(', ', $this->parts[ObjectPartsEnum::EXTENDS->name]);
        } catch (Exception $exception) {
        }

        try {
            // implements    $telegramAppFactory->webhook()->start();

            $file .= ' implements '.implode(', ', $this->parts[ObjectPartsEnum::IMPLEMENTS->name]);
        } catch (Exception $exception) {
        }

        $file .= PHP_EOL.'{';

        try {
            // functions
            foreach ($this->getPart(ObjectPartsEnum::FUNCTION) as $function) {
                $function = str_replace(PHP_EOL, PHP_EOL."{$this->spaces}", $function);
                $file .= "\n{$this->spaces}".$function;
            }
        } catch (Exception $exception) {
        }

        if ($file[-1] === '{') {
            $file .= "\n{$this->spaces}//...";
        }

        $file .= PHP_EOL.'}'.PHP_EOL;

        return $file;
    }

    public function setClassname(string $className): static
    {
        $this->setPart(ObjectPartsEnum::CLASSNAME, $className);

        return $this;
    }

    public function setNamespace(string $namespace): static
    {
        $this->setPart(ObjectPartsEnum::NAMESPACE, $namespace);

        return $this;
    }

    public function setUse(string $className): static
    {
        $this->setPart(ObjectPartsEnum::USE, $className);

        return $this;
    }

    public function setFunction(string $template): static
    {
        $this->setPart(ObjectPartsEnum::FUNCTION, $template);

        return $this;
    }

    protected function setPart(ObjectPartsEnum $part, string $value): void
    {
        $this->parts[$part->name][] = $value;
    }

    protected function getPart(ObjectPartsEnum $part): array
    {
        return $this->parts[$part->name] ?? throw new Exception("part $part->name not found");
    }
}
