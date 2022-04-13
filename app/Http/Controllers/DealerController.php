<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\DealerTypeCredit;
use App\Models\Log;
use App\Models\TypeCredit;
use App\Models\User;
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
    public function dealerTypeCredit(int $id, Request $request): JsonResponse
    {
        $DealerTypeCredits = null;

        if ($id > 0) {
            $DealerTypeCredits = DealerTypeCredit::where('dealer_id', '=', $id)
                ->whereNull('deleted')
                ->orderBy('id', 'desc')
                ->get();
        }
        $TypeCredits = TypeCredit::whereNull('deleted')
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'success' => true,
            'data' => [
                'DealerTypeCredits' => $DealerTypeCredits,
                'TypeCredits' => $TypeCredits,
            ]
        ], 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getDataById($id, Request $request): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => $this->getDealer($id)
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
    public function addOrEdit($id, Request $request): JsonResponse
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
        $Dealer->save();

        DealerTypeCredit::where('dealer_id', '=', $Dealer->id)
            ->whereNull('deleted')
            ->update(['deleted' => 1]);
        if ($request->type_credits) {
           foreach ($request->type_credits as $type_credit_id) {
               $DealerTypeCredit = new DealerTypeCredit();
               $DealerTypeCredit->dealer_id = $Dealer->id;
               $DealerTypeCredit->type_credit_id = $type_credit_id;
               $DealerTypeCredit->deleted = null;
               $DealerTypeCredit->save();
           }
        }

        return response()->json([
            'success' => true,
            'data' => $this->getDealer($Dealer->id)
        ], 200);
    }

    public function dealersList(Request $request): JsonResponse
    {
        $dealers = DB::table('dealers')
            ->select([
                'dealers.*',
                DB::raw("DATE_FORMAT(dealers.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('dealers.deleted')
            ->distinct();
        if ($request->filter) {
            $dealers = $dealers
                ->where('dealers.name', 'like', $request->filter . '%')
                ->orWhere('dealers.full_name', 'like', $request->filter . '%')
                ->orWhere('dealers.phone1', 'like', $request->filter . '%')
                ->orWhere('dealers.phone2', 'like', $request->filter . '%')
                ->orWhere('dealers.email', 'like', $request->filter . '%')
                ->orWhere('dealers.description', 'like', $request->filter . '%')
            ;
        }

        $dealers = $this->standardOrderBy($dealers, $request, 'id', 'desc');
        $dealers = $this->standardPagination($dealers, $request);

        return response()->json([
            'success' => true,
            'data' => $dealers
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function getDealer($id): mixed
    {
        return Dealer::where('id', '=', $id)
            ->with('dealer_type_credits')
            ->first()
            ;
    }


    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteDealer($id, Request $request): JsonResponse
    {
        /* @var $Dealer Dealer */
        $Dealer = Dealer::findOrFail($id);
        $Dealer->deleted = true;
        $Dealer->save();
        User::where('dealer_id', '=', $Dealer->id)->update(['deleted' => 1]);

        return  response()->json([
            'success' => true
        ]);
    }
}
