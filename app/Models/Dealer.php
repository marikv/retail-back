<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Dealer
 * @package App\Models
 *
 * @property int $id
 * @property string $contract_number
 * @property string $contract_date
 * @property string $name
 * @property string $full_name
 * @property string $address_fiz
 * @property string $address_jju
 * @property string $phone1
 * @property string $phone2
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property string $idno
 * @property string $logo
 * @property string $administrator
 * @property string $director_general
 * @property string $director_executiv
 * @property string $description
 * @property string $tip_capital
 * @property string $bank_name
 * @property string $bank_cb
 * @property string $bank_iban
 * @property string $bank_swift
 * @property string $bank_tva
 * @property string $bank_valuta
 * @property int $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Dealer extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function dealer_products(): HasMany
    {
        return $this->hasMany(DealerProduct::class)->whereNull('deleted');
    }
}
