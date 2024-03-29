<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 *
 * Class TypeCredit
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
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property int|null $months_fix
 * @property int|null $months_min
 * @property int|null $months_max
 * @property float|null $sum_min
 * @property float|null $sum_max
 * @property float|null $dobinda
 * @property boolean|null $dobinda_is_percent
 * @property float|null $comision
 * @property boolean|null $comision_is_percent
 * @property float|null $comision_admin
 * @property boolean|null $comision_admin_is_percent
 * @property float|null $percent_comision_magazin
 * @property float|null $percent_bonus_magazin
 * @property boolean $is_shop_fee
 * @property boolean $deleted
 * @property string $description_mini
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class TypeCredit extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return HasMany
     */
    public function dealer_type_credits(): HasMany
    {
        return $this->hasMany(DealerTypeCredit::class);
    }
}
