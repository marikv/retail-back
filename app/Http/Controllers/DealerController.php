<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Log;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DealerController extends Controller
{

    public function getDataById($id, Request $request)
    {
        if ($id) {
            return response([
                'success' => true,
                'data' => $this->getDealer($id)
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [ 'message' => 'nu este id' ]
        ], 200);
    }

    public function addOrEdit($id, Request $request)
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

        return response()->json([
            'success' => true,
            'data' => $this->getDealer($Dealer->id)
        ], 200);
    }

    public function dealersList(Request $request)
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

        return response([
            'success' => true,
            'data' => $dealers
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function getDealer($id)
    {
        return Dealer::findOrFail($id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Application|Response|ResponseFactory
     */
    public function deleteDealer($id, Request $request)
    {
        /* @var $Dealer Dealer */
        $Dealer = Dealer::findOrFail($id);
        $Dealer->deleted = true;
        $Dealer->save();
        User::where('dealer_id', '=', $Dealer->id)->update(['deleted' => 1]);

        return  response([
            'success' => true
        ]);
    }
}
