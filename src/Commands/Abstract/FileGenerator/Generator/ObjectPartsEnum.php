<?php

declare(strict_types=1);

namespace Lowel\Telepath\Commands\Abstract\FileGenerator\Generator;

enum ObjectPartsEnum
{
    case NAMESPACE;
    case CLASSNAME;
    case USE;
    case EXTENDS;
    case IMPLEMENTS;
    case FUNCTION;

}
