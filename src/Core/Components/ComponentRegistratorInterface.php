<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Components;

use Illuminate\Contracts\Foundation\Application;

/**
 * Component registration interface
 */
interface ComponentRegistratorInterface
{
    /**
     * Contains definition for component registration process
     *
     * look TelepathServiceProvider::bindComponents method
     *
     * @link \Lowel\Telepath\TelepathServiceProvider::bindComponents()
     */
    public static function register(Application $app): void;
}
