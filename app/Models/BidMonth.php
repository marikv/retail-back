<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BidMonth
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
 * @property integer $bid_id
 * @property string $date
 * @property float $imprumut_per_luna
 * @property float $dobinda_per_luna
 * @property float $comision_per_luna
 * @property float $comision_admin_per_luna
 * @property float $total_per_luna
 * @property boolean|null $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 */
class BidMonth extends Model
{
    use HasFactory;

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }
}
