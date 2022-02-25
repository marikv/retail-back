<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class ChatMessage
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
 * @property integer $bid_id
 * @property integer $from_user_id
 * @property integer $to_user_id
 * @property integer $file_id
 * @property string $message
 * @property integer $deleted_by_user_id
 * @property boolean $deleted
 * @property string $created_at
 * @property string $updated_at
 */
class ChatMessage extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    /**
     * @return BelongsTo
     */
    public function from_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function to_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo
     */
    public function deleted_by_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param int|null $toUserId
     * @param string|null $message
     * @param int|null $bidId
     * @param int|null $fileId
     * @return void
     */
    public static function sendNewMessage(int $toUserId = null, string $message = null, int $bidId = null, int $fileId = null): void
    {
        $ChatMessage = new self();
        $ChatMessage->from_user_id = Auth::id();
        $ChatMessage->to_user_id = $toUserId;
        $ChatMessage->message = $message;
        $ChatMessage->bid_id = $bidId;
        $ChatMessage->file_id = $fileId;
        $ChatMessage->save();
    }
}
