<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 *
 * Class Log
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
 * @property int $id
 * @property int $user_id
 * @property int $entity_id
 * @property string $entity_name
 * @property string $ip
 * @property string $operation
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Log extends Model
{
    use HasFactory;

    public const OPERATION_DELETE = 'd';
    public const OPERATION_ADD = 'a';
    public const OPERATION_VIEW = 'v';
    public const OPERATION_EDIT = 'e';
    public const OPERATION_LOGIN = 'l';
    public const OPERATION_LOGOUT = 'o';
    public const OPERATION_NAMES = [
        self::OPERATION_DELETE => 'Ștergere',
        self::OPERATION_ADD => 'Adăugare',
        self::OPERATION_EDIT => 'Redactare',
        self::OPERATION_LOGIN => 'Login',
        self::OPERATION_LOGOUT => 'Logout',
        self::OPERATION_VIEW => 'Vizualizare',
    ];

    public const MODULE_DEFAULT = 0;
    public const MODULE_DEALERS = 1;
    public const MODULE_USERS = 2;
    public const MODULE_CASHES = 3;
    public const MODULE_SETTINGS = 6;
    public const MODULE_CLIENTS = 7;
    public const MODULE_FILES = 8;
    public const MODULE_PRODUCTS = 9;
    public const MODULE_SERVICES = 10;
    public const MODULE_NAMES = [
        self::MODULE_DEFAULT => '',
        self::MODULE_DEALERS => 'Dealeri',
        self::MODULE_USERS => 'Utilizatori',
        self::MODULE_CASHES => 'Plăți',
        self::MODULE_SETTINGS => 'Settings',
        self::MODULE_CLIENTS => 'Clienți',
        self::MODULE_FILES => 'Fișiere',
        self::MODULE_PRODUCTS => 'Producte',
        self::MODULE_SERVICES => 'Servicii',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public static function addNewLog(Request $request = null,
                                             $entity_name = Log::MODULE_DEFAULT,
                                             $operation = Log::OPERATION_EDIT,
                                             $entity_id = null,
                                             $description = null,
                                             $user_id = null,
                                             $created_at = null
    ): void
    {
        $model = new self();

        $model->entity_name = $entity_name;
        $model->operation = $operation;

        if ($created_at) {
            $model->created_at = $created_at;
        }

        if ($request) {
            $model->ip = $request->ip();
        }

        if (!$user_id) {
            if (Auth::user()) {
                $model->user_id = Auth::user()->id;
            } else {
                $model->user_id = null;
            }
        } else {
            $model->user_id = $user_id;
        }

        if ($entity_id) {
            $model->entity_id = $entity_id;
        }

        if ($description) {
            $model->description = $description;
        }

        $model->save();
    }
}
