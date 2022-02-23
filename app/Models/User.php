<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone1
 * @property string $password
 * @property int|null $role_id
 * @property int|null $dealer_id
 * @property string $avatar
 * @property string $remember_token
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const USER_ROLE_ADMIN = 1;
    public const USER_ROLE_DEALER = 2;
    public const USER_ROLE_EXECUTOR = 3;

    public const USER_ROLES = [
        self::USER_ROLE_ADMIN => 'Admin',
        self::USER_ROLE_DEALER => 'Dealer',
        self::USER_ROLE_EXECUTOR => 'Executor',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
