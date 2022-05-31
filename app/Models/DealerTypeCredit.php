<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DealerProduct
 * @package App\Models
 *
 * @property int $id
 * @property int $type_credit_id
 * @property int $dealer_id
 * @property float $percent_comision_magazin
 * @property float $percent_bonus_magazin
 * @property int $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 */
class DealerTypeCredit extends Model
{
    use HasFactory;

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function type_credit(): BelongsTo
    {
        return $this->belongsTo(TypeCredit::class);
    }
}
