<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\ChatMessage;
use App\Models\ChatMessageRead;
use App\Models\ProductInvoice;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatMessageController extends Controller
{
    public function sendChatMessage(Request $request): JsonResponse
    {
        $bid_id = $request->bid_id;
        $message = $request->message;
        $toUserId = null;

        if ($bid_id) {
            $Bid = Bid::findOrFail($bid_id);
            if (Auth::user()->role_id === User::USER_ROLE_DEALER) {
                $toUserId = $Bid->execute_user_id;
            }
            if (Auth::user()->role_id !== User::USER_ROLE_DEALER) {
                $toUserId = $Bid->user_id;
            }
        }
        ChatMessage::sendNewMessage($toUserId, $message, $bid_id);

        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }

    public function setChatMessagesRead(Request $request): JsonResponse
    {
        if (is_array($request->ids) && count($request->ids)) {

            foreach ($request->ids as $iValue) {
                $ChatMessageRead = ChatMessageRead::where('chat_message_id', '=', $iValue)
                    ->where('user_id', '=', Auth::id())
                    ->first();
                if ($ChatMessageRead && !$ChatMessageRead->read) {
                    $ChatMessageRead->read = 1;
                    $ChatMessageRead->save();
                }
                if (!$ChatMessageRead) {
                    $ChatMessageRead = new ChatMessageRead();
                    $ChatMessageRead->user_id = Auth::id();
                    $ChatMessageRead->chat_message_id = $iValue;
                    $ChatMessageRead->read = 1;
                    $ChatMessageRead->save();
                }
            }

        }

        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }


    public function getChatMessage(Request $request): JsonResponse
    {
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
                'chat_message_reads.read',
                DB::raw("DATE_FORMAT(chat_messages.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->leftJoin('users as from_user', 'from_user.id', '=', 'chat_messages.from_user_id')
            ->leftJoin('users as to_user', 'to_user.id', '=', 'chat_messages.to_user_id')
            ->leftJoin('chat_message_reads', static function ($join) {
                $join->on('chat_message_reads.chat_message_id', '=', 'chat_messages.id')
                    ->where('chat_message_reads.user_id', '=', Auth::id())
                    ->where('chat_message_reads.read', '=', 1);
            })
            ->leftJoin('files', 'files.id', '=', 'chat_messages.file_id')
            ->whereNull('chat_messages.deleted')
            ->where('chat_messages.bid_id', '=', $request->bid_id)
            ->distinct();
        $ChatMessages = self::standardOrderByStatic($ChatMessages, $request, 'id', 'desc');
        $ChatMessages = self::standardPaginationStatic($ChatMessages, $request);

        $ChatMessagesForLoop = $ChatMessages->items();
        foreach ($ChatMessagesForLoop as $k => $v) {
            if (!$v->read) {
                $ChatMessageRead = ChatMessageRead::where('chat_message_id', '=', $v->id)
                ->where('user_id', '=', Auth::id())
                ->whereNull('read')
                ->first();
                if ($ChatMessageRead && !$ChatMessageRead->read) {
                    $ChatMessageRead->read = 1;
                    $ChatMessageRead->save();
                } else if (!$ChatMessageRead) {
                    $ChatMessageRead = new ChatMessageRead();
                    $ChatMessageRead->user_id = Auth::id();
                    $ChatMessageRead->chat_message_id = $v->id;
                    $ChatMessageRead->read = 1;
                    $ChatMessageRead->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $ChatMessages
        ]);
    }
}
