<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Client
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
 * @property string $description
 * @property boolean $deleted
 *
 * @property string $created_at
 * @property string $updated_at
 */
class Client extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
