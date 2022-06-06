<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\Integration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{

    /**
     * @param $id
     * @param Request $request
     * @param PaymentRepository $paymentRepository
     * @return JsonResponse
     */
    public function getDataById($id, Request $request, PaymentRepository $paymentRepository): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => $paymentRepository->getById($id)
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [ 'message' => 'nu este id' ]
        ], 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @param PaymentRepository $paymentRepository
     * @return JsonResponse
     * @throws \JsonException
     */
    public function addOrEdit($id, Request $request, PaymentRepository $paymentRepository): JsonResponse
    {
        if ((int)$id > 0) {
            $Payment = Payment::findOrFail($id);
        } else {
            $Payment = new Payment();
        }
        if ($request->paymentSumFact && !$Payment->payment_sum_fact) {
            $Payment->payment_sum_fact = $request->paymentSumFact;
            $Payment->beznal = $request->beznal ? 1 : null;
            $Payment->date_time_fact = date('Y-m-d H:i:s');
            $Payment->user_id_fact = Auth::id();
            $Payment->pko_number = null;

            if ($Payment->bid_id) {
                $message = 'Achitare contract '.$Payment->bid_id.' suma '.$Payment->payment_sum_fact;
                Log::addNewLog($request, Log::MODULE_BIDS, Log::OPERATION_EDIT, $Payment->bid_id, $message);
            }
            $Payment->save();

            // Integration::exportPaymentToCash($Payment);
        } else if ($request->paymentSumFact) {
            $Payment->payment_sum_fact = $request->paymentSumFact;
            $Payment->beznal = $request->beznal ? 1 : null;
        }
        $Payment->save();

        return response()->json([
            'success' => true,
            'data' => [
                'payment' => $paymentRepository->getById($Payment->id),
            ],
        ], 200);
    }

    /**
     * @param Request $request
     * @param PaymentRepository $paymentRepository
     * @return JsonResponse
     */
    public function paymentsList(Request $request, PaymentRepository $paymentRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;
        $contractNumber = $request->contractNumber;
        $paymentsInWaiting = $request->paymentsInWaiting;
        $bid_id = $request->bid_id;
        $dealer_id = $request->dealer_id;

        return response()->json([
            'success' => true,
            'data' => $paymentRepository->list($filter, $pagination, [
                'contractNumber' => $contractNumber,
                'paymentsInWaiting' => $paymentsInWaiting,
                'bid_id' => $bid_id,
                'dealer_id' => $dealer_id,
            ]),
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @param PaymentRepository $paymentRepository
     * @return JsonResponse
     */
    public function deletePayment($id, Request $request, PaymentRepository $paymentRepository): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'data' => [ 'message' => 'nu este id' ]
            ], 200);
        }

        $success = $paymentRepository->delete($id);

        if ($success) {
            Log::addNewLog(
                $request,
                Log::MODULE_PAYMENTS,
                Log::OPERATION_DELETE,
                $id
            );
        }

        return  response()->json([
            'success' => $success
        ]);
    }
}
