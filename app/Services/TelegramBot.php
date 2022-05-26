<?php

namespace App\Services;

class TelegramBot
{
    static function sendMessage($text, $request)
    {
        if ($request->server('REMOTE_ADDR') === '192.168.0.66') {
            // RetailCreditBoxMdTestBot
            $botApi = '5115962267:AAEL1Px1mbD-qP7YLOEF54csSZreM1Do7cE';
            $data = [
                'chat_id' => '5271360131',
                'text' => $text,
            ];
        } else {
            $botApi = '5376123873:AAEM1DbxWmOJoFLvDpgOfXh4a9hA5wb9bN8';
            $data = [
                'chat_id' => '5271360131',
                'text' => $text,
            ];
        }
        $link = 'https://api.telegram.org/bot'.$botApi.'/sendMessage?'.http_build_query($data);
        $response = file_get_contents($link);
    }
}
