<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class Integration
{

    /**
     * @param Payment $Payment
     * @return void
     * @throws \JsonException
     */
    public static function exportPaymentToCash(Payment $Payment): void
    {
        // metoda e buna dar nu o folosim
        return;

        $CASH_APP_URL = env('CASH_APP_URL');
        $CASH_APP_USERNAME = env('CASH_APP_USERNAME');
        $CASH_APP_PASSWORD = env('CASH_APP_PASSWORD');

        if ($CASH_APP_URL && $CASH_APP_USERNAME && $CASH_APP_PASSWORD) {

            $responseApi = Http::post($CASH_APP_URL . '/auth/login', [
                'email' => $CASH_APP_USERNAME,
                'password' => $CASH_APP_PASSWORD,
            ]);

            if ($responseApi->failed()) {
                $Payment->cash_api_response = $responseApi->body();

            } else {
                $responseApiCashToken = $responseApi->json('token');

                $responseApi2 = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $responseApiCashToken
                ])->post($CASH_APP_URL . '/add-new-payment-from-api', [
                    'sum' => $Payment->payment_sum_fact,
                    'beznal' => $Payment->beznal,
                    'retail_payment_id' => $Payment->id,
                    'kind' => 1,
                    'cash_name_id' => 1,
                    'desc' => 'test',
                ]);

                if ($responseApi2->failed()) {
                    $Payment->cash_api_response = $responseApi2->body();

                } else {
                    $responseApi2Json = $responseApi2->json();

                    $Payment->cash_api_response = json_encode($responseApi2, JSON_THROW_ON_ERROR);

                    if (isset($responseApi2Json['success'], $responseApi2Json['data'], $responseApi2Json['cash_id'], $responseApi2Json['cash'])) {
                        $Payment->cash_id = $responseApi2Json['cash_id'];
                        $Payment->pko_number = $responseApi2Json['cash']['pko_number'];
                    }
                }
            }
        } else {
            $Payment->cash_api_response = 'Nu sunt definite env-urile CASH_APP_URL, CASH_APP_USERNAME, CASH_APP_PASSWORD';
        }
        $Payment->save();
    }
}
