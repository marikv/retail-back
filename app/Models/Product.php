<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *  *
 * @property int $id
 * @property string $name
 * @property boolean $deleted
 * @property string $created_at
 * @property string $updated_at

 */
class Product extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function type_credits(): HasMany
    {
        return $this->hasMany(TypeCredit::class);
    }
}
