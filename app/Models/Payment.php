<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Payment
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
 * @property integer $type
 * @property integer|null $pko_number
 * @property integer|null $cash_id
 * @property string $cash_api_response
 * @property string $date_time
 * @property string $date_time_fact
 * @property boolean|null $beznal
 * @property integer|null $payment_kind_id
 * @property integer|null $payment_cash_id
 * @property integer|null $bid_id
 * @property integer|null $dealer_id
 * @property integer|null $user_id
 * @property integer|null $user_id_fact
 * @property integer|null $client_id
 * @property double|null $payment_sum
 * @property double|null $payment_sum_fact
 * @property string|null $description
 * @property boolean $deleted
 *
 * @property string $created_at
 * @property string $updated_at
 */
class Payment extends Model
{
    use HasFactory;

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function user_fact(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_fact');
    }

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

//    public static function generatePkoNumber(int $type = 1, int $cash_name_id = null, $beznal = null)
//    {
//        if ((int)$beznal === 1) {
//            return null;
//        }
//        $currentYear = date('Y');
//
//        $maxNumberValue =  self::whereNull('deleted')
//            ->where('type', '=', $type)
//            ->where('date_time', '>=', $currentYear . '-01-01 00:00:00');
//        if ($cash_name_id > 0) {
//            $maxNumberValue = $maxNumberValue->where('cash_name_id', '=', $cash_name_id);
//        }
//        if ($beznal) {
//            $maxNumberValue = $maxNumberValue->whereNotNull('beznal');
//        } else {
//            $maxNumberValue = $maxNumberValue->whereNull('beznal');
//        }
//        $maxNumberValue = $maxNumberValue->max('pko_number');
//        $maxNumberValue = $maxNumberValue ? (int)$maxNumberValue : 0;
//
//        ++$maxNumberValue;
//        return $maxNumberValue;
//    }
}
