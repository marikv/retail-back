<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\ChatMessage;
use App\Models\ChatMessageRead;
use App\Models\User;
use App\Repositories\BidRepository;
use App\Repositories\ChatMessageRepository;
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
        $toUserId = $request->user_id;

        if ($toUserId) {

        }
        elseif ($bid_id) {
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

    public function checkNewMessages(Request $request, BidRepository $bidRepository): JsonResponse
    {
        $activeModule = $request->activeModule ?: '';

        $ChatMessages = DB::table('chat_messages')
            ->select(['chat_messages.*'])
            ->leftJoin('chat_message_reads', static function ($join) {
                $join->on('chat_message_reads.chat_message_id', '=', 'chat_messages.id')
                    ->where('chat_message_reads.user_id', '=', Auth::id());
            })
            ->where('chat_messages.to_user_id', '=', Auth::id())
            ->whereNull('chat_message_reads.id')
            ->limit(100)
            ->get();
        $ChatMessagesArray = $ChatMessages->toArray();
        $Bids = null;
        if (count($ChatMessagesArray)) {
            $newArr = [];
            foreach ($ChatMessagesArray as $ChatMessage) {
                if (!empty($ChatMessage->bid_id)) {
                    $Bid = Bid::whereNull('deleted')->where('id', '=', $ChatMessage->bid_id)->first();
                    if (!$Bid) {
                        continue;
                    }
                }
                $newArr[] = $ChatMessage;
            }
            $ChatMessagesArray = $newArr;
            unset($newArr);
        }
        if ($activeModule === 'Calculator' || $activeModule === 'Bids') {

            // $Bids = BidController::getItems($request)->get();
            //$BidsIdArray = $Bids->map(function ($bid) { return $bid->id; })->toArray();
            //todo:
            $Bids = $bidRepository->list(null, null, ['activeModule' => $activeModule])->items();
            $BidsIdArray = collect($Bids)->map(function ($bid) { return $bid->id; })->toArray();

            if (count($BidsIdArray)) {

                $BidChatMessages = DB::table('chat_messages')
                    ->select(['chat_messages.*'])
                    ->leftJoin('chat_message_reads', static function ($join) {
                        $join->on('chat_message_reads.chat_message_id', '=', 'chat_messages.id')
                            ->where('chat_message_reads.user_id', '=', Auth::id());
                    })
                    ->whereIn('chat_messages.bid_id', $BidsIdArray)
                    ->whereNull('chat_message_reads.id')
                    ->limit(100)
                    ->get();

                if (count($BidChatMessages)) {

                    foreach ($BidChatMessages as $BidChatMessage) {

                        if (count($ChatMessagesArray)) {

                            $ChatMessagesIdArray = array_map(static function ($ChatMessage) {
                                return $ChatMessage->id;
                            },$ChatMessagesArray);
                        }
                        else {
                            $ChatMessagesIdArray = [];
                            $ChatMessages = [];
                        }

                        if (!in_array($BidChatMessage->id, $ChatMessagesIdArray, true)) {

                            $ChatMessagesArray = [...$ChatMessagesArray, $BidChatMessage];
                        }
                    }
                }
            }
        }

        $newBids = 0;
        if (Auth::user()->role_id !== User::USER_ROLE_DEALER) {
            $newBids = Bid::whereNull('deleted')
                ->where('status_id', '=', Bid::BID_STATUS_NEW)
                ->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'unreadMessages' => $ChatMessagesArray,
                'bids' => $Bids,
                'newBids' => $newBids,
            ],
        ]);
    }

    public function setChatMessagesRead(Request $request): JsonResponse
    {
        if (is_array($request->ids) && count($request->ids)) {

            foreach ($request->ids as $iValue) {
                $ChatMessageRead = ChatMessageRead::where('chat_message_id', '=', $iValue)
                    ->where('user_id', '=', Auth::id())
                    ->first();
                if (!$ChatMessageRead) {
                    $ChatMessageRead = new ChatMessageRead();
                    $ChatMessageRead->user_id = Auth::id();
                    $ChatMessageRead->chat_message_id = $iValue;
                    $ChatMessageRead->save();
                }
            }

        }

        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }


    public function getChatMessage(Request $request, ChatMessageRepository $chatMessageRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;
        $activeModule = $request->activeModule;
        $bid_id = $request->bid_id;
        $user_id = $request->user_id;

        return response()->json([
            'success' => true,
            'data' => $chatMessageRepository->list($filter, $pagination, ['bid_id' => $bid_id, 'user_id' => $user_id, 'activeModule' => $activeModule])
        ]);
    }


    public function chatGetFullList(Request $request, ChatMessageRepository $chatMessageRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;
        $activeModule = $request->activeModule;
        $bid_id = $request->bid_id;

        return response()->json([
            'success' => true,
            'data' => $chatMessageRepository->chatGetFullList($filter, $pagination, ['bid_id' => $bid_id, 'activeModule' => $activeModule])
        ]);
    }
}
