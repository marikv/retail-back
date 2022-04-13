<?php

namespace App\Services;

use App\Models\Bid;
use App\Models\TypeCredit;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class CalculatorService
{


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
                    if ($TypeCredit->is_shop_fee) {
                        $dobindaPerLuna = $sum * $dobinda / 100;
                    } else {
                        $dobindaPerLuna = $imprumtPerLuna * $dobinda / 100;
                    }
                } else {
                    if ($TypeCredit->is_shop_fee) {
                        $dobindaPerLuna = $sum * $dobinda;
                    } else {
                        $dobindaPerLuna = $imprumtPerLuna * $dobinda;
                    }
                }
            }

            $comision = max((float)$TypeCredit->comision, 0);
            $comisionPerLuna = 0;
            if ($comision > 0) {
                if ($TypeCredit->comision_is_percent) {
                    if ($TypeCredit->is_shop_fee) {
                        $comisionPerLuna = $sum * $comision / 100;
                    } else {
                        $comisionPerLuna = $imprumtPerLuna * $comision / 100;
                    }
                } else {
                    if ($TypeCredit->is_shop_fee) {
                        $comisionPerLuna = $sum * $comision;
                    } else {
                        $comisionPerLuna = $imprumtPerLuna * $comision;
                    }
                }
            }

            $comisionAdmin = max((float)$TypeCredit->comision_admin, 0);
            $comisionAdminPerLuna = 0;
            if ($comisionAdmin > 0) {
                if ($TypeCredit->comision_admin_is_percent) {
                    if ($TypeCredit->is_shop_fee) {
                        $comisionAdminPerLuna = $sum * $comisionAdmin / 100;
                    } else {
                        $comisionAdminPerLuna = $imprumtPerLuna * $comisionAdmin / 100;
                    }
                } else {
                    if ($TypeCredit->is_shop_fee) {
                        $comisionAdminPerLuna = $sum * $comisionAdmin;
                    } else {
                        $comisionAdminPerLuna = $imprumtPerLuna * $comisionAdmin;
                    }
                }
            }

            $totalPerLuna = $imprumtPerLuna + $dobindaPerLuna + $comisionPerLuna + $comisionAdminPerLuna;
            $tabel = [];
            $dobindaTotal = 0;
            $comisionTotal = 0;
            $comisionAdminTotal = 0;
            $totalPerToateLunile = 0;
            $arrayForDae = array(
                array(
                    'date' => Carbon::parse($date)->format('Y-m-d'),
                    'sum' => (-1) * $sum,
                )
            );
            for ($i = 1; $i <= $months; $i++) {
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
                $arrayForDae[] = array(
                    'date' => $CarbonDateNew->format('Y-m-d'),
                    'sum' => $totalPerLuna,
                );
            }

            $FinFuncService = new FinFuncService();

            $xirrSums = collect($arrayForDae)->map(static function ($arr) {
                return $arr['sum'];
            })->toArray();

            $xirrTimestamps = collect($arrayForDae)->map(static function ($arr) {
                return Carbon::parse($arr['date'])->timestamp;
            })->toArray();

            $xirrDates = collect($arrayForDae)->map(static function ($arr) {
                return Carbon::parse($arr['date'])->format('d.m.Y');
            })->toArray();

            $DAE = $FinFuncService->XIRR($xirrSums, $xirrTimestamps, 0.1);

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
                    'DAE' => round($DAE * 100, 2),
                    'xirrSums' => $xirrSums,
                    'xirrTimestamps' => $xirrTimestamps,
                    'xirrDates' => $xirrDates,
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
