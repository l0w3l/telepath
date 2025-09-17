<?php

declare(strict_types=1);

namespace Lowel\Telepath\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

trait InvokeAbleTrait
{
    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    private static function invokeStaticClassWithArgs(object $class, array $args = [], string $customMethod = '__invoke'): mixed
    {
        $reflectionMethod = new ReflectionMethod($class, $customMethod);
        $parameters = $reflectionMethod->getParameters();

        $app = app();
        $instances = [];
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            $value = null;

            if (array_key_exists($name, $args)) {
                $value = $args[$name];
            } elseif ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                $value = $app->make($type->getName(), $args);
            }

            $instances[] = $value;
        }

        return $reflectionMethod->invokeArgs($class, $instances);
    }
}
