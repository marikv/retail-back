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
 * @property integer $refused_user_id
 * @property string $refused_date_time
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
class Bid extends Model
{
    use HasFactory;

    public const BID_STATUS_NEW = 0;
    public const BID_STATUS_IN_WORK = 1;
    public const BID_STATUS_APPROVED = 2;
    public const BID_STATUS_REFUSED = 3;
    public const BID_STATUS_SIGNED_CONTRACT = 4;

    public const BID_STATUS_NAMES = [
        self::BID_STATUS_NEW => 'Cerere nouă',
        self::BID_STATUS_IN_WORK => 'Cerere în lucru',
        self::BID_STATUS_APPROVED => 'Cerere aprobată',
        self::BID_STATUS_REFUSED => 'Cerere refuzată',
        self::BID_STATUS_SIGNED_CONTRACT => 'Contract semnat',
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
    public function files(): HasMany
    {
        return $this->hasMany(File::class)->whereNull('deleted');
    }

    /**
     * @param int $type
     * @param float $sum
     * @param int $months
     * @param string $date
     * @param Bid|null $Bid
     * @return array
     */
    #[ArrayShape(['success' => "bool", 'data' => "array"])]
    public static function getCalcResults(int $type, float $sum, int $months, string $date, Bid $Bid = null): array
    {
        try {
            if (!$type) {
                throw new \RuntimeException('alege tipul');
            }
            if (!$sum) {
                throw new \RuntimeException('alege suma');
            }
            if (!$months) {
                throw new \RuntimeException('alege termenul');
            }
            if (!$date) {
                throw new \RuntimeException('alege data');
            }

            $TypeCredit = TypeCredit::findOrFail($type);

            if ($Bid) {
                if ((float)$Bid->sum_max_permis > 0) {
                    if ($sum > (float)$Bid->sum_max_permis) {
                        throw new \RuntimeException('Suma este mai mare de ' . $Bid->sum_max_permis . ' lei');
                    }
                }
                else if ((float)$Bid->sum_max > 0) {
                    if ($sum > (float)$Bid->sum_max) {
                        throw new \RuntimeException('Suma este mai mare de ' . $Bid->sum_max . ' lei');
                    }
                }
                else if ((float)$TypeCredit->sum_max > 0) {
                    if ($sum > (float)$TypeCredit->sum_max) {
                        throw new \RuntimeException('Suma este mai mare de ' . $TypeCredit->sum_max . ' lei');
                    }
                }
            }
            else if ((float)$TypeCredit->sum_max > 0 && $sum > (float)$TypeCredit->sum_max) {
                throw new \RuntimeException('Suma este mai mare de ' . $TypeCredit->sum_max . ' lei');
            }
            if ($sum < (float)$TypeCredit->sum_min) {
                throw new \RuntimeException('Suma este mai mica de ' . $TypeCredit->sum_max . ' lei');
            }
            if ($months > (float)$TypeCredit->months_fix) {
                throw new \RuntimeException('Termenul mai mare de ' . $TypeCredit->months_fix . ' luni');
            }
            if ($months < (float)$TypeCredit->months_fix) {
                throw new \RuntimeException('Termenul mai mic de ' . $TypeCredit->months_fix . ' luni');
            }

            $imprumtPerLuna = $sum / $months;

            $dobinda = max((float)$TypeCredit->dobinda, 0);
            $dobindaPerLuna = 0;
            if ($dobinda > 0) {
                if ($TypeCredit->dobinda_is_percent) {
                    $dobindaPerLuna = $imprumtPerLuna * $dobinda / 100;
                } else {
                    $dobindaPerLuna = $imprumtPerLuna * $dobinda;
                }
            }

            $comision = max((float)$TypeCredit->comision, 0);
            $comisionPerLuna = 0;
            if ($comision > 0) {
                if ($TypeCredit->comision_is_percent) {
                    $comisionPerLuna = $imprumtPerLuna * $comision / 100;
                } else {
                    $comisionPerLuna = $imprumtPerLuna * $comision;
                }
            }

            $comisionAdmin = max((float)$TypeCredit->comision_admin, 0);
            $comisionAdminPerLuna = 0;
            if ($comisionAdmin > 0) {
                if ($TypeCredit->comision_admin_is_percent) {
                    // $comisionAdminPerLuna = $imprumtPerLuna * $comisionAdmin / 100;
                    $comisionAdminPerLuna = $sum * $comisionAdmin / 100;
                } else {
                    $comisionAdminPerLuna = $imprumtPerLuna * $comisionAdmin;
                }
            }

            $totalPerLuna = $imprumtPerLuna + $dobindaPerLuna + $comisionPerLuna + $comisionAdminPerLuna;
            $tabel = [];
            $dobindaTotal = 0;
            $comisionTotal = 0;
            $comisionAdminTotal = 0;
            $totalPerToateLunile = 0;
            for ($i = 0; $i < $months; $i++) {
                $CarbonDateNew = self::getNextFreeDate(Carbon::parse($date)->addMonths($i));
                $tabel[$i] = [
                    'data' => $CarbonDateNew->format('d.m.Y'),
                    'imprumtPerLuna' => round($imprumtPerLuna, 2),
                    'dobindaPerLuna' => round($dobindaPerLuna, 2),
                    'comisionPerLuna' => round($comisionPerLuna, 2),
                    'comisionAdminPerLuna' => round($comisionAdminPerLuna, 2),
                    'totalPerLuna' => round($totalPerLuna, 2),
                ];
                $dobindaTotal += $dobindaPerLuna;
                $comisionTotal += $comisionPerLuna;
                $comisionAdminTotal += $comisionAdminPerLuna;
                $totalPerToateLunile += $totalPerLuna;
            }
            $tabelTotal = [
                'imprumut' => round($sum, 2),
                'dobinda' => round($dobindaTotal, 2),
                'comision' => round($comisionTotal, 2),
                'comisionAdmin' => round($comisionAdminTotal, 2),
                'total' => round($totalPerToateLunile, 2),
            ];

            $calcResults = [
                'success' => true,
                'data' => [
                    'imprumtPerLuna' => round($imprumtPerLuna, 2),
                    'dobindaPerLuna' => round($dobindaPerLuna, 2),
                    'comisionPerLuna' => round($comisionPerLuna, 2),
                    'comisionAdminPerLuna' => round($comisionAdminPerLuna, 2),
                    'totalPerLuna' => round($totalPerLuna, 2),
                    'APR' => round(($totalPerLuna / $imprumtPerLuna - 1) * 100, 2),
                    'coef1PerLuna' => round(($totalPerLuna - $imprumtPerLuna) / $totalPerLuna, 6),
                    'tabel' => $tabel,
                    'tabelTotal' => $tabelTotal,
                ],
            ];
        } catch (\Exception $e) {

            $calcResults = [
                'success' => false,
                'data' => [
                    'message' => $e->getMessage()
                ],
            ];
        }
        return $calcResults;
    }

    /**
     * @param Carbon $CarbonDateNew
     * @return Carbon
     */
    protected static function getNextFreeDate(Carbon $CarbonDateNew): Carbon
    {
        if ($CarbonDateNew->isSunday()) {
            return self::getNextFreeDate($CarbonDateNew->addDay());
        }

        if ($CarbonDateNew->isSaturday()) {
            return self::getNextFreeDate($CarbonDateNew->addDays(2));
        }

        if (in_array($CarbonDateNew->format('d.m'), ['01.01', '07.07', '25.12', '31.12'])) {
            return self::getNextFreeDate($CarbonDateNew->addDay());
        }

        // zilele de paste
        if (in_array($CarbonDateNew->format('d.m.Y'), [
            '24.04.2022',
            '16.04.2023',
            '05.05.2024',
            '20.04.2025',
            '12.04.2026',
            '02.05.2027',
            '16.04.2028',
            '08.04.2029',
            '28.04.2030',
            '13.04.2031',
            '02.05.2032'
        ])) {
            return self::getNextFreeDate($CarbonDateNew->addDay());
        }

        return $CarbonDateNew;
    }
}
