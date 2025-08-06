<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router\Context\Executor\Traits;

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
    private function invokeStaticClassWithArgs(object $class, array $args = []): mixed
    {
        $reflectionMethod = new ReflectionMethod($class, '__invoke');
        $parameters = $reflectionMethod->getParameters();

        $app = app();
        $instances = [];
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (array_key_exists($name, $args)) {
                $instances[] = $args[$name];
            } elseif ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                $instances[] = $app->make($type->getName(), $args);
            } else {
                $instances[] = null;
            }
        }

        return $reflectionMethod->invokeArgs($class, $instances);
    }
}
