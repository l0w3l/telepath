<?php

declare(strict_types=1);

namespace Lowel\Telepath\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Lowel\Telepath\Models\Traits\TgUserAuthenticatableTrait;

/**
 * @property int $telegram_id
 */
class TgUser extends Model implements Authenticatable
{
    use TgUserAuthenticatableTrait;

    protected $primaryKey = 'telegram_id';

    public $incrementing = false;

    protected $fillable = [
        'telegram_id',
        'first_name',
        'last_name',
        'username',
        'is_bot',
        'language_code',
        'raw',
    ];

    protected $casts = [
        'raw' => 'array',
    ];
}
