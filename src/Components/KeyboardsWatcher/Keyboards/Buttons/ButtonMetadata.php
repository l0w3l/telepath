<?php

declare(strict_types=1);

namespace Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons;

use Lowel\Telepath\Components\KeyboardsWatcher\Keyboards\Buttons\Attributes\InlineButtonAttribute;
use ReflectionMethod;

readonly class ButtonMetadata
{
    /**
     * @param  null|InlineButtonAttribute  $metadata
     */
    public function __construct(
        public string $name,
        public mixed $metadata
    ) {}

    public static function fromReflectionMethod(ReflectionMethod $reflectionMethod): ?self
    {
        // only methods with '*Button' postfix
        if (! str_ends_with($reflectionMethod->getName(), 'Button')) {
            return null;
        }

        // collect attributes
        $metadata = null;
        foreach ($reflectionMethod->getAttributes() as $attr) {
            $instance = $attr->newInstance();

            if ($instance instanceof InlineButtonAttribute) {
                $metadata = $instance;
            }

        }

        return new self(
            $reflectionMethod->getName(),
            $metadata ?? new InlineButtonAttribute($reflectionMethod->getName()),
        );
    }

    public function getTextMethodName(): string
    {
        return $this->name.'Text';
    }
}
