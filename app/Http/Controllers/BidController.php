<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\BidMonth;
use App\Models\ChatMessage;
use App\Models\Client;
use App\Models\Dealer;
use App\Models\TypeCredit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        $items = DB::table('bids')
            ->select([
                'bids.*',
                'dealers.logo',
                'dealers.name as dealer_name',
                'users.name as user_name',
                'type_credits.name as type_credits_name',
                'clients.last_name as client_last_name',
                'clients.first_name as client_first_name',
                DB::raw("DATE_FORMAT(bids.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('bids.deleted')
            ->leftJoin('users', 'users.id', '=', 'bids.user_id')
            ->leftJoin('dealers', 'dealers.id', '=', 'bids.dealer_id')
            ->leftJoin('clients', 'clients.id', '=', 'bids.client_id')
            ->leftJoin('type_credits', 'type_credits.id', '=', 'bids.type_credit_id')
            ->distinct();

        if (Auth::user()->role_id === User::USER_ROLE_DEALER) {
            $items = $items
                ->where('bids.user_id', '=', Auth::user()->id)
                ->where('bids.dealer_id', '=', Auth::user()->dealer_id)
                // ->where('bids.created_at', '>', date('Y-m-d') . ' 00:00:00')
            ;
        }

        $items = $this->standardOrderBy($items, $request, 'id', 'desc');
        $items = $this->standardPagination($items, $request);

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
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
                'data' => $this->getBid($id)
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
    public function setBidStatus($id, Request $request): JsonResponse
    {
        if ($id) {

            $Bid = Bid::findOrFail($id);

            if ((int)$Bid->status_id === Bid::BID_STATUS_NEW && (int)$request->status_id === Bid::BID_STATUS_IN_WORK) {

                $Bid->execute_user_id = Auth::id();
                $Bid->execute_date_time = date('Y-m-d H:i:s');
                ChatMessage::sendNewMessage($Bid->user_id, 'Cerere în lucru', $Bid->id);

            } else if ((int)$Bid->status_id === Bid::BID_STATUS_IN_WORK && (int)$request->status_id === Bid::BID_STATUS_APPROVED) {

                $Bid->sum_max_permis = $request->sum_max_permis;
                $Bid->approved_user_id = Auth::id();
                $Bid->approved_date_time = date('Y-m-d H:i:s');
                ChatMessage::sendNewMessage($Bid->user_id, 'Cerere aprobată', $Bid->id);

            } else if ((int)$Bid->status_id === Bid::BID_STATUS_IN_WORK && (int)$request->status_id === Bid::BID_STATUS_REFUSED) {

                $Bid->refused_user_id = Auth::id();
                $Bid->refused_date_time = date('Y-m-d H:i:s');
                ChatMessage::sendNewMessage($Bid->user_id, 'Cerere refuzată', $Bid->id);

            } else if ((int)$Bid->status_id === (int)$request->status_id && (int)$request->status_id === Bid::BID_STATUS_IN_WORK) {

                $Bid = Bid::findOrFail($id)->with('execute_user');

                return response()->json([
                    'success' => false,
                    'data' => [
                        'message' => 'Cererea este deja în lucru,  executor ' . ($Bid && $Bid->executor_user()->name),
                        'closeBid' => true,
                    ]
                ], 200);
            }

            $Bid->status_id = $request->status_id;
            $Bid->save();

            return response()->json([
                'success' => true,
                'data' => $this->getBid($id)
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [ 'message' => 'nu este id' ]
        ], 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function getBid($id): mixed
    {
        return Bid::where('id', '=', $id)
            ->with('client')
            ->with('type_credit')
            ->with('dealer')
            ->with('user')
            ->with('execute_user')
            ->with('bid_months')
            ->first();
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function addOrEdit(int $id, Request $request): JsonResponse
    {
        try {
            $Client = null;
            if (!empty($request->client_id)) {
                $Client = Client::where('id', '=', $request->client_id)->orderBy('id', 'desc')->first();
            }
            if (!$Client && !empty($request->buletin_sn) && strlen($request->buletin_sn) === 9) {
                $Client = Client::where('buletin_sn', '=', $request->buletin_sn)->orderBy('id', 'desc')->first();
            }
            if (!$Client && !empty($request->buletin_idnp) && strlen($request->buletin_idnp) === 13) {
                $Client = Client::where('buletin_idnp', '=', $request->buletin_idnp)->orderBy('id', 'desc')->first();
            }
            if (!$Client) {
                $Client = new Client();
            }
            $TypeCredit = TypeCredit::findOrFail($request->type_credit_id);

            $Client->first_name = $request->first_name;
            $Client->last_name = $request->last_name;
            $Client->birth_date = strlen($request->birth_date) === 10 ? Carbon::parse($request->birth_date)->format('Y-m-d') : '';
            $Client->phone1 = $request->phone1;
            $Client->buletin_sn = $request->buletin_sn;
            $Client->buletin_idnp = $request->buletin_idnp;
            $Client->localitate = $request->localitate;
            $Client->street = $request->street;
            $Client->save();

            if (!$id) {
                $Bid = new Bid();
                $Bid->status_id = Bid::BID_STATUS_NEW;
            } else {
                $Bid = Bid::findOrFail($id);
            }

            $Bid->user_id = Auth::id();
            $Bid->dealer_id = Auth::user()->dealer_id;
            $Bid->client_id = $Client->id;
            $Bid->type_credit_id = $TypeCredit->id;
            $Bid->type_credit_name = $TypeCredit->name;
            $Bid->first_pay_date = strlen($request->first_pay_date) === 10 ? Carbon::parse($request->first_pay_date)->format('Y-m-d') : '';
            $Bid->months = $request->months;
            $Bid->imprumut = $request->imprumut;

            $Bid->total = $request->calc_results['tabelTotal']['total'];
            $Bid->total_dobinda = $request->total_dobinda;
            $Bid->total_comision = $request->total_comision;
            $Bid->total_comision_admin = $request->total_comision_admin;
            $Bid->apr = $request->calc_results['APR'];
            $Bid->apy = null;// todo:
            $Bid->coef = $request->calc_results['coef1PerLuna'];

            $Bid->months_fix = $TypeCredit->months_fix;
            $Bid->months_min = $TypeCredit->months_min;
            $Bid->months_max = $TypeCredit->months_max;
            $Bid->sum_min = $TypeCredit->sum_min;
            $Bid->sum_max = $TypeCredit->sum_max;
            $Bid->dobinda = $TypeCredit->dobinda;
            $Bid->dobinda_is_percent = $TypeCredit->dobinda_is_percent;
            $Bid->comision = $TypeCredit->comision;
            $Bid->comision_is_percent = $TypeCredit->comision_is_percent;
            $Bid->comision_admin = $TypeCredit->comision_admin;
            $Bid->comision_admin_is_percent = $TypeCredit->comision_admin_is_percent;
            $Bid->percent_comision_magazin = $TypeCredit->percent_comision_magazin;
            $Bid->percent_bonus_magazin = $TypeCredit->percent_bonus_magazin;

            $Bid->first_name = $request->first_name;
            $Bid->last_name = $request->last_name;
            $Bid->birth_date = strlen($request->birth_date) === 10 ? Carbon::parse($request->birth_date)->format('Y-m-d') : '';
            $Bid->phone1 = $request->phone1;
            $Bid->buletin_sn = $request->buletin_sn;
            $Bid->buletin_idnp = $request->buletin_idnp;
            $Bid->localitate = $request->localitate;
            $Bid->street = $request->street;
            $Bid->buletin_date_till = $request->buletin_date_till;
            $Bid->buletin_office = $request->buletin_office;
            $Bid->region = $request->region;
            $Bid->flat = $request->flat;
            $Bid->house = $request->house;
            $Bid->save();

            foreach ($request->calc_results['tabel'] as $row) {
                $BidMonths = new BidMonth();
                $BidMonths->bid_id = $Bid->id;
                $BidMonths->date = Carbon::parse($row['data'])->format('Y-m-d');
                $BidMonths->imprumut_per_luna = (float)$row['imprumtPerLuna'];
                $BidMonths->dobinda_per_luna = (float)$row['dobindaPerLuna'];
                $BidMonths->comision_per_luna = (float)$row['comisionPerLuna'];
                $BidMonths->comision_admin_per_luna = (float)$row['comisionAdminPerLuna'];
                $BidMonths->total_per_luna = (float)$row['totalPerLuna'];
                $BidMonths->save();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'bid' => $this->getBid($Bid->id),
                ]
            ]);
        }catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'data' => [
                    'message' => $e->getMessage(),
                ]
            ]);
        }
    }


    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function delete($id, Request $request): JsonResponse
    {
        /* @var $Dealer Dealer */
        $Dealer = Bid::findOrFail($id);
        $Dealer->deleted = true;
        $Dealer->save();

        return  response()->json([
            'success' => true
        ]);
    }
}
