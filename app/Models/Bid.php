<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Bid
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
 * @property integer $execute_user_id
 * @property string $execute_date_time
 * @property integer $approved_user_id
 * @property string $approved_date_time
 * @property integer $signed_user_id
 * @property string $signed_date_time
 * @property integer $payed_user_id
 * @property string $payed_date_time
 * @property integer $refused_user_id
 * @property string $refused_date_time
 * @property integer $client_id
 * @property integer $type_credit_id
 * @property string $type_credit_name
 * @property string $bid_date
 * @property integer $months
 * @property float $imprumut
 * @property float $sum_max_permis
 * @property float $total
 * @property float $total_comision_admin
 * @property float $total_comision
 * @property float $total_dobinda
 * @property float $apr
 * @property float $dae
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
 * @property boolean $is_shop_fee
 * @property string $first_name
 * @property string $last_name
 * @property string $phone1
 * @property string $email
 * @property string $birth_date
 * @property string $buletin_sn
 * @property string $buletin_idnp
 * @property string $buletin_date_till
 * @property string $buletin_office
 * @property string $region
 * @property boolean|null $same_addresses
 * @property string $localitate
 * @property string $street
 * @property string $house
 * @property string $flat
 * @property string $region_reg
 * @property string $localitate_reg
 * @property string $street_reg
 * @property string $house_reg
 * @property string $flat_reg
 * @property string $who_is_cont_pers1
 * @property string $phone_cont_pers1
 * @property string $last_name_cont_pers1
 * @property string $first_name_cont_pers1
 * @property string $produs
 * @property string $who_is_cont_pers2
 * @property string $phone_cont_pers2
 * @property string $last_name_cont_pers2
 * @property string $first_name_cont_pers2
 * @property boolean $deleted
 * @property string $created_at
 * @property string $updated_at
 */
class Bid extends Model
{
    use HasFactory;

    public const BID_STATUS_NEW = 0;
    public const BID_STATUS_IN_WORK = 1;
    public const BID_STATUS_APPROVED = 2;
    public const BID_STATUS_REFUSED = 3;
    public const BID_STATUS_CONTRACT_SIGNED = 4;
    public const BID_STATUS_CONTRACT_PAYED = 5;

    public const BID_STATUS_NAMES = [
        self::BID_STATUS_NEW => 'Cerere nouă',
        self::BID_STATUS_IN_WORK => 'Cerere în lucru',
        self::BID_STATUS_APPROVED => 'Cerere aprobată',
        self::BID_STATUS_REFUSED => 'Cerere refuzată',
        self::BID_STATUS_CONTRACT_SIGNED => 'Contract semnat',
        self::BID_STATUS_CONTRACT_PAYED => 'Contract închis',
    ];

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

    /**
     * @return BelongsTo
     */
    public function execute_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function approved_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function signed_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function refused_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function bid_months(): HasMany
    {
        return $this->hasMany(BidMonth::class)->whereNull('deleted');
    }

    /**
     * @return HasMany
     */
    public function bid_scorings(): HasMany
    {
        return $this->hasMany(BidScoring::class);
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class)->whereNull('deleted');
    }
}
