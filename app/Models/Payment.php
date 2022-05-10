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
 * @property string $date_time
 * @property string $date_time_fact
 * @property boolean|null $beznal
 * @property integer|null $payment_kind_id
 * @property integer|null $payment_cash_id
 * @property integer|null $bid_id
 * @property integer|null $dealer_id
 * @property integer|null $user_id
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

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
