<?php

namespace App\Repositories;

use App\Models\ChatMessage;
use App\Models\ChatMessage as Model;
use App\Models\ChatMessageRead;
use App\Models\User;
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
     * @return array
     */
    public function chatGetFullList(string $filter = null, array $pagination = null, array $options = []): array
    {
        $authUser = $this->authUser;
        $itemsArray = [];
        $k = 0;
        if ($authUser->role_id !== User::USER_ROLE_DEALER) {

            $Users = DB::table('users')
                ->select(['users.*'])
                ->whereNull('users.deleted')
                ->where('users.id', '!=', $authUser->id)
                ->whereIn('users.role_id', [User::USER_ROLE_ADMIN, User::USER_ROLE_EXECUTOR])
                ->orderBy('users.name', 'asc')
                ->get();

            foreach ($Users as $k=>$v) {
                $itemsArray[$k] = (array)$v;
                $ChatMessage =  ChatMessage::whereNull('deleted')
                    ->whereNull('bid_id')
                    ->where(static function ($items) use ($v) {
                        $items->where('to_user_id', '=', $v->id)
                            ->orWhere('from_user_id', '=', $v->id);
                    })
                    ->orderBy('id', 'desc')
                    ->first();
                $itemsArray[$k]['lastMessage'] = $ChatMessage;
                $itemsArray[$k]['sortDate'] = $ChatMessage && $ChatMessage->created_at ? $ChatMessage->created_at : $v->created_at;
            }
        }

        $Bids = DB::table('bids')
            ->select(['bids.*'])
            ->whereNull('bids.deleted')
            ->where(function ($items) use ($authUser) {
                $items->where('bids.user_id', '=', $authUser->id)
                    ->orWhere('bids.signed_user_id', '=', $authUser->id)
                    ->orWhere('bids.execute_user_id', '=', $authUser->id)
                    ->orWhere('bids.refused_user_id', '=', $authUser->id)
                    ->orWhere('bids.approved_user_id', '=', $authUser->id)
                ;
            })
            ->orderBy('bids.id', 'desc')
            ->limit(50)
            ->get();

        foreach ($Bids as $k2=>$v) {
            $itemsArray[$k + $k2 + 1] = (array)$v;
            $ChatMessage =  ChatMessage::whereNull('deleted')
                ->where('bid_id', '=', $v->id)
                ->orderBy('id', 'desc')
                ->first();
            $itemsArray[$k + $k2 + 1]['lastMessage'] = $ChatMessage;
            $itemsArray[$k + $k2 + 1]['sortDate'] = $ChatMessage && $ChatMessage->created_at ? $ChatMessage->created_at : $v->created_at;
        }
        return [... array_values($itemsArray)];
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
            ->whereNull('chat_messages.deleted');
        if (!empty($options['user_id'])) {
            $ChatMessages->where(static function ($items) use ($authUser, $options) {
                $items->where(static function ($items) use ($authUser, $options) {
                    $items->where('chat_messages.to_user_id', '=', $authUser->id)
                        ->where('chat_messages.from_user_id', '=', $options['user_id']);
                })->orWhere(static function ($items) use ($authUser, $options) {
                    $items->where('chat_messages.from_user_id', '=', $authUser->id)
                        ->where('chat_messages.to_user_id', '=', $options['user_id']);
                });
            });
        } else if (!empty($options['bid_id'])) {
            $ChatMessages = $ChatMessages->where('chat_messages.bid_id', '=', $options['bid_id']);
        } else {
            $ChatMessages = $ChatMessages->where('chat_messages.bid_id', '=', 999999999);//todo: not exist. empty sql result
        }
        $ChatMessages = $ChatMessages->distinct();
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
}
