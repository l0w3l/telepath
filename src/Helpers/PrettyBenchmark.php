<?php

declare(strict_types=1);

namespace Lowel\Telepath\Helpers;

use Illuminate\Support\Benchmark;

class PrettyBenchmark
{
    /**
     * Измеряет время выполнения кода и выводит в человеко-читаемом формате.
     *
     * @return float время в секундах
     */
    public static function measure(\Closure $callback): float
    {
        return Benchmark::measure($callback);
    }

    public static function dump(string $label, \Closure $closure): float
    {
        $ms = self::measure($closure);

        dump(self::formatResult($label, $ms));

        return $ms;
    }

    /**
     * Форматирование вывода.
     */
    protected static function formatResult(string $label, float $ms): string
    {
        $usPrecision = 2;

        // если <1 ms — показываем в микросекундах
        $us = $ms * 1000.0;

        $formatted = number_format($us, $usPrecision, '.', '').' μs';

        return "{$label}: {$formatted}";
    }
}
