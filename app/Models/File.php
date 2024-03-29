<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class File
 * @package App\Models
 *
 * @method static self findOrFail(integer $id)
 * @method static self whereNull(string $column_name)
 * @method static self whereIn(string $column_name, $array_values)
 * @method static self where(string $column_name, string $operator, $value)
 * @method static self orderBy(string $column_name, string $descOrAsc)
 * @method static self whereNotNull(string $column_name)
 * @method static self first()
 * @method max(string $column_name)
 *
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $web_path
 * @property string $path
 * @property string $size
 * @property string $mimetype
 * @property string $extension
 * @property integer $client_id
 * @property integer $dealer_id
 * @property integer $user_id
 * @property integer $payment_id
 * @property int $bid_id
 * @property integer $added_by_user_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 */
class File extends Model
{
    use HasFactory;

    public const FILE_TYPE_LOGO = 1;
    public const FILE_TYPE_AVATAR = 2;
    public const FILE_TYPE_UNSIGN_CONTRACT = 3;
    public const FILE_TYPE_SIGN_CONTRACT = 4;
    public const FILE_TYPE_BULETIN_1 = 5;
    public const FILE_TYPE_BULETIN_2 = 6;
    public const FILE_TYPE_OTHERS_1 = 7;
    public const FILE_TYPE_OTHERS_2 = 8;
    public const FILE_TYPE_ANEXA = 9;

    public const FILE_TYPES = [
        self::FILE_TYPE_LOGO => 'Logo',
        self::FILE_TYPE_AVATAR => 'Avatar',
        self::FILE_TYPE_UNSIGN_CONTRACT => 'Contract ne semnat',
        self::FILE_TYPE_SIGN_CONTRACT => 'Contract semnat',
        self::FILE_TYPE_ANEXA => 'Anexa',
        self::FILE_TYPE_BULETIN_1 => 'Copia actului de identitate 1',
        self::FILE_TYPE_BULETIN_2 => 'Copia actului de identitate 2',
        self::FILE_TYPE_OTHERS_1 => 'Alte fișiere 1',
        self::FILE_TYPE_OTHERS_2 => 'Alte fișiere 2',
    ];

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    public function added_by_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }
}
