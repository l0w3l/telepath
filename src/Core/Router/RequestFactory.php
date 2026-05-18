<?php

declare(strict_types=1);

namespace Lowel\Telepath\Core\Router;

use Illuminate\Http\Request;
use Lowel\Telepath\Enums\UpdateTypeEnum;
use Phptg\BotApi\Type\Update\Update;

class RequestFactory
{
    public static function fromUpdate(UpdateTypeEnum $updateType, Update $update): Request
    {
        $text = UpdateTypeEnum::extractText($update, $updateType);

        if (null !== $text && str_starts_with($text, '/')) {
            $text = substr($text, 1);
        }

        $request = Request::create(
            uri: sprintf(
                'telepath/%s/%s',
                $updateType->value,
                urlencode($text ?? '')
            ),
            method: 'POST',
            content: $update->getRaw(),
        );

        $request->attributes->set('telepath.update', $update);

        return $request;
    }

    public static function fromRaw(Update $update, UpdateTypeEnum $updateType, ?string $data = ''): Request
    {
        $request = Request::create(
            uri: sprintf(
                'telepath/%s/%s',
                $updateType->value,
                urlencode($data ?? '')
            ),
            method: 'POST',
            content: $update->getRaw(),
        );

        $request->attributes->set('telepath.update', $update);

        return $request;
    }
}
