<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\DealerProduct;
use App\Models\DealerTypeCredit;
use App\Models\Log;
use App\Models\Product;
use App\Models\TypeCredit;
use App\Models\User;
use App\Repositories\DealerRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DealerController extends Controller
{
    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function dealerProducts(int $id, Request $request): JsonResponse
    {
        $DealerProducts = null;
        $DealerTypeCredits = [];

        if ($id > 0) {
            $DealerProducts = DealerProduct::where('dealer_id', '=', $id)
                ->whereNull('deleted')
                ->orderBy('id', 'desc')
                ->get();

            $TypeCredits = TypeCredit::whereNull('deleted')
                ->orderBy('name', 'desc')
                ->get();
            /* @var $TypeCredit TypeCredit */
            foreach ($TypeCredits as $k => $TypeCredit) {
                $DealerTypeCredits[$k] = $TypeCredit->toArray();
                $DealerTypeCredits[$k]['DealerTypeCredits'] = DealerTypeCredit::where('dealer_id', '=', $id)
                    ->whereNull('deleted')
                    ->where('type_credit_id', '=', $TypeCredit->id)
                    ->orderBy('id', 'desc')
                    ->first();
            }
        }
        $Products = Product::whereNull('deleted')
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'success' => true,
            'data' => [
                'DealerProducts' => $DealerProducts,
                'Products' => $Products,
                'DealerTypeCredits' => $DealerTypeCredits
            ]
        ], 200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function addOrEditDealerTypeCredit(int $id, Request $request): JsonResponse
    {
        if ($id) {
            $DealerTyepCredit = DealerTypeCredit::findOrFail($id);
        } else {
            $DealerTyepCredit = DealerTypeCredit::whereNull('deleted')
                ->where('dealer_id', '=', $request->dealer_id)
                ->where('type_credit_id', '=', $request->type_credit_id)
                ->first();
            if (!$DealerTyepCredit) {
                $DealerTyepCredit = new DealerTypeCredit();
            }
            $DealerTyepCredit->dealer_id = $request->dealer_id;
            $DealerTyepCredit->type_credit_id = $request->type_credit_id;
        }
        $DealerTyepCredit->percent_bonus_magazin = $request->percent_bonus_magazin;
        $DealerTyepCredit->percent_comision_magazin = $request->percent_comision_magazin;
        $DealerTyepCredit->save();

        return response()->json([
            'success' => true,
            'data' => []
        ], 200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function editDealerProducts(int $id, Request $request): JsonResponse
    {
        $productIdsArray = $request->products;

        $DealerProducts = DealerProduct::where('dealer_id', '=', $id)->whereNull('deleted')->get();

        if ($productIdsArray) {
            $productIdsCurrentInDb = [];

            if ($DealerProducts) {
                /* @var $DealerProduct DealerProduct */

                foreach ($DealerProducts as $DealerProduct) {
                    $productIdsCurrentInDb[] = (int)$DealerProduct->product_id;
                }
            }

            foreach ($productIdsArray as $product_id) {
                if (in_array((int)$product_id, $productIdsCurrentInDb, true)) {
                    continue;
                }

                $DealerProduct = DealerProduct::where('dealer_id', '=', $id)
                    ->where('product_id', '=', $product_id)
                    ->whereNotNull('deleted')
                    ->first();
                if (!$DealerProduct) {
                    $DealerProduct = new DealerProduct();
                    $DealerProduct->dealer_id = $id;
                    $DealerProduct->product_id = $product_id;
                }
                $DealerProduct->deleted = null;
                $DealerProduct->save();
            }

            foreach ($productIdsCurrentInDb as $productIdCurrentInDb) {
                if (!in_array((int)$productIdCurrentInDb, $productIdsArray, true)) {
                    $DealerProduct = DealerProduct::where('dealer_id', '=', $id)
                        ->where('product_id', '=', $productIdCurrentInDb)
                        ->whereNull('deleted')
                        ->first();
                    if ($DealerProduct) {
                        $DealerProduct->deleted = true;
                        $DealerProduct->save();
                    }
                }
            }
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
    public function getDataById($id, Request $request, DealerRepository $dealerRepository): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => $dealerRepository->getById($id),
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
    public function addOrEdit($id, Request $request, DealerRepository $dealerRepository): JsonResponse
    {
        if ((int)$id > 0) {
            $Dealer = Dealer::findOrFail($id);
        } else {
            $Dealer = new Dealer();
        }
        $Dealer->name = $request->name;
        $Dealer->full_name = $request->full_name;
        $Dealer->idno = $request->idno;
        $Dealer->address_fiz = $request->address_fiz;
        $Dealer->address_jju = $request->address_jju;
        $Dealer->phone1 = $request->phone1;
        $Dealer->phone2 = $request->phone2;
        $Dealer->fax = $request->fax;
        $Dealer->email = $request->email;
        $Dealer->website = $request->website;
        $Dealer->director_general = $request->director_general;
        $Dealer->description = $request->description;
        $Dealer->bank_name = $request->bank_name;
        $Dealer->bank_cb = $request->bank_cb;
        $Dealer->bank_iban = $request->bank_iban;
        $Dealer->bank_swift = $request->bank_swift;
        $Dealer->bank_tva = $request->bank_tva;
        $Dealer->bank_valuta = $request->bank_valuta;
        $Dealer->contract_date = Carbon::parse($request->contract_date)->format('Y-m-d');
        $Dealer->save();

        return response()->json([
            'success' => true,
            'data' => $dealerRepository->getById($Dealer->id)
        ], 200);
    }

    public function dealersList(Request $request, DealerRepository $dealerRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;

        return response()->json([
            'success' => true,
            'data' => $dealerRepository->list($filter, $pagination)
        ]);
    }


    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteDealer($id, Request $request, DealerRepository $dealerRepository): JsonResponse
    {
        User::where('dealer_id', '=', $id)->update(['deleted' => 1]);
        $dealerRepository->delete($id);

        return  response()->json([
            'success' => true
        ]);
    }
}
