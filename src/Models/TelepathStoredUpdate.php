<?php

declare(strict_types=1);

namespace Lowel\Telepath\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Phptg\BotApi\Type\Update\Update;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @property int $id
 * @property Update $instance
 * @property DateTimeInterface $updated_at
 * @property DateTimeInterface $created_at
 *
 * @mixin Builder
 */
class TelepathStoredUpdate extends Model
{
    protected $fillable = [
        'instance',
    ];

    public function instance(): Attribute
    {
        $serializer = new Serializer([new ObjectNormalizer(
            nameConverter: new CamelCaseToSnakeCaseNameConverter,
        )], [new JsonEncoder]);

        return Attribute::make(
            get: fn (string $value) => $serializer->deserialize($value, Update::class, 'json'),
            set: function (Update|array|string $value) use ($serializer) {
                if (is_array($value)) {
                    $value = Update::fromJson(json_encode($value, JSON_THROW_ON_ERROR));
                } elseif (is_string($value)) {
                    $value = Update::fromJson($value);
                }

                return $serializer->serialize($value, 'json');
            },
        );
    }
}
