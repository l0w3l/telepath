<?php

declare(strict_types=1);

namespace Lowel\Telepath\Traits;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;

trait InvokeAbleTrait
{
    private static function invokeCallableWithArgs(callable $callable, array $args = []): mixed
    {
        try {
            $reflection = self::getReflectionForCallable($callable);
            $parameters = $reflection->getParameters();

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

            return $reflection->invokeArgs(
                $instances
            );
        } catch (ReflectionException|BindingResolutionException $e) {
            throw new \RuntimeException('Cannot invoke callable.', previous: $e);
        }
    }

    /**
     * @throws ReflectionException
     */
    private static function getReflectionForCallable(callable $callable): ReflectionMethod|ReflectionFunction
    {
        if (is_array($callable)) {
            // ['ClassName', 'method'] or [$object, 'method']
            return new ReflectionMethod($callable[0], $callable[1]);
        }

        if (is_string($callable) && str_contains($callable, '::')) {
            // 'ClassName::method'
            return new ReflectionMethod(...explode('::', $callable, 2));
        }

        if ($callable instanceof Closure) {
            return new ReflectionFunction($callable);
        }

        if (is_object($callable) && method_exists($callable, '__invoke')) {
            return new ReflectionMethod($callable, '__invoke');
        }

        return new ReflectionFunction($callable);
    }
}
