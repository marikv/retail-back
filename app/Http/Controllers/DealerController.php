<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\DealerProduct;
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

        if ($id > 0) {
            $DealerProducts = DealerProduct::where('dealer_id', '=', $id)
                ->whereNull('deleted')
                ->orderBy('id', 'desc')
                ->get();
        }
        $Products = Product::whereNull('deleted')
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'success' => true,
            'data' => [
                'DealerProducts' => $DealerProducts,
                'Products' => $Products,
            ]
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
        $Dealer->description = $request->description;
        $Dealer->bank_name = $request->bank_name;
        $Dealer->bank_cb = $request->bank_cb;
        $Dealer->bank_iban = $request->bank_iban;
        $Dealer->bank_valuta = $request->bank_valuta;
        $Dealer->contract_date = Carbon::parse($request->contract_date)->format('Y-m-d');
        $Dealer->save();

        DealerProduct::where('dealer_id', '=', $Dealer->id)
            ->whereNull('deleted')
            ->update(['deleted' => 1]);
        if ($request->products) {
           foreach ($request->products as $product_id) {
               $DealerProduct = new DealerProduct();
               $DealerProduct->dealer_id = $Dealer->id;
               $DealerProduct->product_id = $product_id;
               $DealerProduct->deleted = null;
               $DealerProduct->save();
           }
        }

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
