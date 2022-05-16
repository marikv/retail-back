<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     */
    public function addOrEdit($id, Request $request, PaymentRepository $paymentRepository): JsonResponse
    {
        if ((int)$id > 0) {
            $Payment = Payment::findOrFail($id);
        } else {
            $Payment = new Payment();
        }
        $Payment->save();

        return response()->json([
            'success' => true,
            'data' => $paymentRepository->getById($Payment->id)
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

        return response()->json([
            'success' => true,
            'data' => $paymentRepository->list($filter, $pagination, ['contractNumber' => $contractNumber])
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
