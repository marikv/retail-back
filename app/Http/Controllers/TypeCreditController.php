<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\TypeCredit;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Repositories\TypeCreditRepository;
use App\Services\CalculatorService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypeCreditController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request): JsonResponse
    {
        $type = (int)$request->type;
        $date = (string)$request->date;
        $sum = (float)str_replace(array(',', ' '), array('.', ''), $request->sum);
        $months = (int)$request->months;

        $calcResults = CalculatorService::getCalcResults($type, $sum, $months, $date);

        return response()->json($calcResults, 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getDataById($id, Request $request, TypeCreditRepository $typeCreditRepository): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => $typeCreditRepository->getById($id),
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
     * @return JsonResponse
     */
    public function productsGetDataById($id, Request $request, ProductRepository $productRepository): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => $productRepository->getById($id),
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
     * @return JsonResponse
     */
    public function addOrEdit($id, Request $request, ProductRepository $productRepository): JsonResponse
    {
        if (Auth::user()->role_id === User::USER_ROLE_ADMIN) {
            if ((int)$id > 0) {
                $TypeCredit = TypeCredit::findOrFail($id);
            } else {
                $TypeCredit = new TypeCredit();
            }
            $name = $request->name;
            if ($request->product_id) {
                /* @var $Product Product */
                $Product = $productRepository->getById($request->product_id);
                $luni = (int)$request->months_fix === 1 ? 'luna' : 'luni';
                $name = $Product->name. ' - ' . (int)$request->months_fix . ' ' . $luni;
            }
            $TypeCredit->name = $name;
            $TypeCredit->description_mini = $request->description_mini;
            $TypeCredit->description = $request->description;
            $TypeCredit->months_fix = $request->months_fix;
            $TypeCredit->months_min = $request->months_min;
            $TypeCredit->months_max = $request->months_max;
            $TypeCredit->sum_min = $request->sum_min;
            $TypeCredit->sum_max = $request->sum_max;
            $TypeCredit->dobinda = $request->dobinda;
            $TypeCredit->dobinda_is_percent = $request->dobinda_is_percent;
            $TypeCredit->comision = $request->comision;
            $TypeCredit->comision_is_percent = $request->comision_is_percent;
            $TypeCredit->comision_admin = $request->comision_admin;
            $TypeCredit->comision_admin_is_percent = $request->comision_admin_is_percent;
            $TypeCredit->percent_bonus_magazin = $request->percent_bonus_magazin;
            $TypeCredit->is_shop_fee = $request->is_shop_fee;
            $TypeCredit->percent_comision_magazin = $request->percent_comision_magazin;
            $TypeCredit->product_id = $request->product_id;
            $TypeCredit->save();
        }

        return response()->json([
            'success' => true,
            'data' => []
        ], 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function addOrEditProduct($id, Request $request): JsonResponse
    {
        if (Auth::user()->role_id === User::USER_ROLE_ADMIN) {
            if ((int)$id > 0) {
                $Product = Product::findOrFail($id);
            } else {
                $Product = new Product();
            }
            $Product->name = $request->name;
            $Product->save();
        }

        return response()->json([
            'success' => true,
            'data' => []
        ], 200);
    }

    /**
     * @param Request $request
     * @param TypeCreditRepository $typeCreditRepository
     * @return JsonResponse
     */
    public function typeCreditsList(Request $request, TypeCreditRepository $typeCreditRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;

        return response()->json([
            'success' => true,
            'data' => $typeCreditRepository->list($filter, $pagination)
        ]);
    }

    /**
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function productsList(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;

        return response()->json([
            'success' => true,
            'data' => $productRepository->list($filter, $pagination)
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function delete($id, Request $request, TypeCreditRepository $typeCreditRepository): JsonResponse
    {
        if (Auth::user()->role_id === User::USER_ROLE_ADMIN) {
            $typeCreditRepository->delete($id);
        }

        return  response()->json([
            'success' => true
        ], 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function productDelete($id, Request $request, ProductRepository $productRepository): JsonResponse
    {
        if (Auth::user()->role_id === User::USER_ROLE_ADMIN) {
            $productRepository->delete($id);
        }

        return  response()->json([
            'success' => true
        ], 200);
    }
}
