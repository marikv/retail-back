<?php

namespace App\Repositories;

use App\Models\ChatMessage as Model;
use App\Models\ChatMessageRead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatMessageRepository extends AbstractCoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }
    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id = 0): mixed
    {
        return $this->startConditions()
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @param string|null $filter
     * @param array|null $pagination
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function list(string $filter = null, array $pagination = null, array $options = []): LengthAwarePaginator
    {
        $authUser = $this->authUser;
        $ChatMessages = DB::table('chat_messages')
            ->select([
                'chat_messages.*',
                'from_user.avatar as from_user_avatar',
                'from_user.name as from_user_name',
                'to_user.avatar as to_user_avatar',
                'to_user.name as to_user_name',
                'files.web_path as file_web_path',
                'files.name as file_name',
                'files.extension as file_extension',
                DB::raw("chat_message_reads.id as `read`"),
                DB::raw("DATE_FORMAT(chat_messages.created_at, '%d.%m.%Y %H:%i') as `created_at2`"),
            ])
            ->leftJoin('users as from_user', 'from_user.id', '=', 'chat_messages.from_user_id')
            ->leftJoin('users as to_user', 'to_user.id', '=', 'chat_messages.to_user_id')
            ->leftJoin('chat_message_reads', static function ($join) use ($authUser) {
                $join->on('chat_message_reads.chat_message_id', '=', 'chat_messages.id')
                    ->where('chat_message_reads.user_id', '=', $authUser->id);
            })
            ->leftJoin('files', 'files.id', '=', 'chat_messages.file_id')
            ->whereNull('chat_messages.deleted')
            ->where('chat_messages.bid_id', '=', $options['bid_id'])
            ->distinct();
        // dd($ChatMessages->toSql());
        $ChatMessages = self::standardOrderByStatic($ChatMessages, $pagination, 'id', 'desc');
        $ChatMessages = self::standardPaginationStatic($ChatMessages, $pagination);

        $ChatMessagesForLoop = $ChatMessages->items();
        foreach ($ChatMessagesForLoop as $k => $v) {
            if (!$v->read) {
                $ChatMessageRead = ChatMessageRead::where('chat_message_id', '=', $v->id)
                    ->where('user_id', '=', Auth::id())
                    ->first();
                if (!$ChatMessageRead) {
                    $ChatMessageRead = new ChatMessageRead();
                    $ChatMessageRead->user_id = Auth::id();
                    $ChatMessageRead->chat_message_id = $v->id;
                    $ChatMessageRead->save();
                }
            }
        }

        return $ChatMessages;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data = [])
    {
        // TODO: Implement create() method.
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id = 0, array $data = [])
    {
        // TODO: Implement update() method.
    }
}
