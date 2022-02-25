<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Contract
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
 * @property integer $status_id
 * @property integer $user_id
 * @property integer $dealer_id
 * @property integer $client_id
 * @property integer $type_credit_id
 * @property string $type_credit_name
 * @property string $first_pay_date
 * @property integer $months
 * @property float $imprumut
 * @property float $sum_max_permis
 * @property float $total
 * @property float $total_comision_admin
 * @property float $total_comision
 * @property float $total_dobinda
 * @property float $apr
 * @property float $apy
 * @property float $coef
 * @property integer $months_fix
 * @property integer $months_min
 * @property integer $months_max
 * @property float $sum_min
 * @property float $sum_max
 * @property float $dobinda
 * @property boolean $dobinda_is_percent
 * @property float $comision
 * @property boolean $comision_is_percent
 * @property float $comision_admin
 * @property boolean $comision_admin_is_percent
 * @property float $percent_comision_magazin
 * @property float $percent_bonus_magazin
 * @property string $first_name
 * @property string $last_name
 * @property string $phone1
 * @property string $birth_date
 * @property string $buletin_sn
 * @property string $buletin_idnp
 * @property string $buletin_date_till
 * @property string $buletin_office
 * @property string $region
 * @property string $localitate
 * @property string $street
 * @property string $house
 * @property string $flat
 * @property boolean $deleted
 *
 * @property string $created_at
 * @property string $updated_at
 */
class Contract extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo
     */
    public function type_credit(): BelongsTo
    {
        return $this->belongsTo(TypeCredit::class);
    }

    /**
     * @return BelongsTo
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
