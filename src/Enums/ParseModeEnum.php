<?php

declare(strict_types=1);

namespace Lowel\Telepath\Enums;

enum ParseModeEnum: string
{
    case HTML = 'HTML';
    case LEGACY = 'Markdown';
    case MARKDOWN = 'MarkdownV2';
}
