<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\BidMonth;
use App\Models\BidScoring;
use App\Models\ChatMessage;
use App\Models\Client;
use App\Models\Dealer;
use App\Models\Log;
use App\Models\TypeCredit;
use App\Models\User;
use App\Repositories\BidRepository;
use App\Repositories\PaymentRepository;
use App\Services\CalculatorService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    /**
     * @param Request $request
     * @param BidRepository $bidRepository
     * @return JsonResponse
     */
    public function getList(Request $request, BidRepository $bidRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;
        $activeModule = $request->activeModule;

        return response()->json([
            'success' => true,
            'data' => $bidRepository->list($filter, $pagination, ['activeModule' => $activeModule])
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @param BidRepository $bidRepository
     * @return JsonResponse
     */
    public function getDataById($id, Request $request, BidRepository $bidRepository): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => $bidRepository->getById($id)
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
     * @param BidRepository $bidRepository
     * @return JsonResponse
     */
    public function changeSum($id, Request $request, BidRepository $bidRepository): JsonResponse
    {
        if ($id) {

            if ((float)$request->new_sum > 0) {

                $Bid = Bid::findOrFail($id);
                $oldSum = $Bid->imprumut;

                try {
                    $calcResults = CalculatorService::getCalcResults($Bid->type_credit_id, (float)$request->new_sum, $Bid->months, $Bid->bid_date, $Bid);
                } catch (\Exception $e) {

                    return response()->json([
                        'success' => false,
                        'data' => [ 'message' => $e->getMessage() ]
                    ], 200);
                }

                if (!$calcResults['success']) {

                    return response()->json($calcResults, 200);
                }

                if (!empty($calcResults['data']['tabelTotal']['total'])) {

                    $calcResults = $calcResults['data'];

                    $Bid->imprumut = (float)$request->new_sum;
                    $Bid->total = $calcResults['tabelTotal']['total'];
                    $Bid->total_dobinda = $calcResults['tabelTotal']['dobinda'];
                    $Bid->total_comision = $calcResults['tabelTotal']['comision'];
                    $Bid->total_comision_admin = $calcResults['tabelTotal']['comisionAdmin'];
                    $Bid->apr = !empty($calcResults['APR']) ? $calcResults['APR'] : null;
                    $Bid->dae = $calcResults['DAE'];
                    $Bid->apy = null;// todo:
                    $Bid->coef = $calcResults['coef1PerLuna'];

                    $Bid->save();

                    BidMonth::where('bid_id', '=', $id)
                        ->whereNull('deleted')
                        ->update(['deleted' => 1]);

                    foreach ($calcResults['tabel'] as $row) {
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

                    $message = 'Suma cererii №' . $Bid->id . ' a fost schimbată din ' . $oldSum. ' în '. $Bid->imprumut;
                    ChatMessage::sendNewMessage($Bid->user_id, $message, $Bid->id, null);

                    Log::addNewLog($request, Log::MODULE_BIDS, Log::OPERATION_EDIT, $Bid->id, $message);

                    return response()->json([
                        'success' => true,
                        'data' => $bidRepository->getById($id)
                    ], 200);
                }
            }
            return response()->json([
                'success' => false,
                'data' => [ 'message' => 'verificați suma' ]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [ 'message' => 'nu este id' ]
        ], 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @param BidRepository $bidRepository
     * @return JsonResponse
     */
    public function setBidStatus($id, Request $request, BidRepository $bidRepository, PaymentRepository $paymentRepository): JsonResponse
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

            } else if ((int)$Bid->status_id === Bid::BID_STATUS_APPROVED && (int)$request->status_id === Bid::BID_STATUS_SIGNED_CONTRACT) {

                $Bid->signed_user_id = Auth::id();
                $Bid->signed_date_time = date('Y-m-d H:i:s');
                ChatMessage::sendNewMessage($Bid->user_id, 'Contract semnat', $Bid->id);
                $paymentRepository->createPayments($Bid);

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

            $message = 'Statusul cererii a fost schimbat în '.Bid::BID_STATUS_NAMES[(int)$Bid->status_id];
            Log::addNewLog($request, Log::MODULE_BIDS, Log::OPERATION_EDIT, $Bid->id, $message);

            $Bid->save();

            return response()->json([
                'success' => true,
                'data' => $bidRepository->getById($id)
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [ 'message' => 'nu este id' ]
        ], 200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws \JsonException
     */
    public function addOrEditScoring(int $id, Request $request): JsonResponse
    {

        if ($id) {
            $BidScoring = BidScoring::where('bid_id', '=', $id)->orderBy('id', 'desc')->first();
            if (!$BidScoring) {
                $BidScoring = new BidScoring();
                $BidScoring->bid_id = $id;
            }
            $BidScoring->json_date = json_encode((array)$request->post(), JSON_THROW_ON_ERROR);
            $BidScoring->save();
        }
        return response()->json([
            'success' => true,
            'data' => []
        ], 200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws \JsonException
     */
    public function bidSaveClientData(int $id, Request $request, BidRepository $bidRepository): JsonResponse
    {

        if ($id) {
            $Bid = Bid::where('id', '=', $id)->first();
            if ($Bid) {
                $Bid->first_name = $request->first_name;
                $Bid->last_name = $request->last_name;
                $Bid->phone1 = $request->phone1;
                $Bid->email = $request->email;
                $Bid->buletin_sn = $request->buletin_sn;
                $Bid->buletin_idnp = $request->buletin_idnp;
                $Bid->buletin_date_till = strlen($request->buletin_date_till) === 10 ? Carbon::parse($request->buletin_date_till)->format('Y-m-d') : '';
                $Bid->buletin_office = $request->buletin_office;
                $Bid->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
                $Bid->region = $request->region;
                $Bid->localitate = $request->localitate;
                $Bid->street = $request->street;
                $Bid->house = $request->house;
                $Bid->flat = $request->flat;
                $Bid->who_is_cont_pers1 = $request->who_is_cont_pers1;
                $Bid->phone_cont_pers1 = $request->phone_cont_pers1;
                $Bid->first_name_cont_pers1 = $request->first_name_cont_pers1;
                $Bid->last_name_cont_pers1 = $request->last_name_cont_pers1;
                $Bid->produs = $request->produs;
                $Bid->who_is_cont_pers2 = $request->who_is_cont_pers2;
                $Bid->phone_cont_pers2 = $request->phone_cont_pers2;
                $Bid->first_name_cont_pers2 = $request->first_name_cont_pers2;
                $Bid->last_name_cont_pers2 = $request->last_name_cont_pers2;
                $Bid->save();
            }
        }
        return response()->json([
            'success' => true,
            'data' => $bidRepository->getById($id)
        ], 200);
    }
    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws \JsonException
     */
    public function getScoring(int $id, Request $request): JsonResponse
    {
        $BidScoring = BidScoring::where('bid_id', '=', $id)->orderBy('id', 'desc')->first();
        return response()->json([
            'success' => true,
            'data' => $BidScoring
        ], 200);
    }
    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function addOrEdit(int $id, Request $request, BidRepository $bidRepository): JsonResponse
    {
        try {
            $Client = null;
            if ($request->client_id) {
                $Client = Client::where('id', '=', $request->client_id)->orderBy('id', 'desc')->first();
            }
            if (!$Client && $request->buletin_sn && strlen($request->buletin_sn) === 9) {
                $Client = Client::where('buletin_sn', '=', $request->buletin_sn)->orderBy('id', 'desc')->first();
            }
            if (!$Client && $request->buletin_idnp && strlen($request->buletin_idnp) === 13) {
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
            $Client->email = $request->email;
            $Client->buletin_sn = $request->buletin_sn;
            $Client->buletin_idnp = $request->buletin_idnp;
            $Client->buletin_date_till = strlen($request->buletin_date_till) === 10 ? Carbon::parse($request->buletin_date_till)->format('Y-m-d') : '';
            $Client->buletin_office = $request->buletin_office;
            $Client->localitate = $request->localitate;
            $Client->street = $request->street;
            $Client->house = $request->house;
            $Client->flat = $request->flat;
            $Client->save();

            if (!$id) {
                $Bid = new Bid();
                $Bid->status_id = Bid::BID_STATUS_NEW;
            } else {
                $Bid = Bid::findOrFail($id);
            }
            /* @var $Bid Bid */

            $Bid->user_id = Auth::id();
            $Bid->dealer_id = Auth::user()->role_id === User::USER_ROLE_DEALER ? Auth::user()->dealer_id : $request->dealer;
            $Bid->client_id = $Client->id;
            $Bid->type_credit_id = $TypeCredit->id;
            $Bid->type_credit_name = $TypeCredit->name;
            $Bid->bid_date = strlen($request->bid_date) === 10 ? Carbon::parse($request->bid_date)->format('Y-m-d') : '';
            $Bid->months = $request->months;
            $Bid->imprumut = $request->imprumut;

            $Bid->total = $request->calc_results['tabelTotal']['total'];
            $Bid->total_dobinda = $request->total_dobinda;
            $Bid->total_comision = $request->total_comision;
            $Bid->total_comision_admin = $request->total_comision_admin;
            $Bid->apr = !empty($request->calc_results['APR']) ? $request->calc_results['APR'] : null;
            $Bid->dae = $request->calc_results['DAE'];
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
            $Bid->is_shop_fee = $TypeCredit->is_shop_fee;

            $Bid->first_name = $request->first_name;
            $Bid->last_name = $request->last_name;
            $Bid->birth_date = strlen($request->birth_date) === 10 ? Carbon::parse($request->birth_date)->format('Y-m-d') : '';
            $Bid->phone1 = $request->phone1;
            $Bid->email = $request->email;
            $Bid->buletin_sn = $request->buletin_sn;
            $Bid->buletin_idnp = $request->buletin_idnp;
            $Bid->buletin_date_till = strlen($request->buletin_date_till) === 10 ? Carbon::parse($request->buletin_date_till)->format('Y-m-d') : '';
            $Bid->buletin_office = $request->buletin_office;
            $Bid->localitate = $request->localitate;
            $Bid->street = $request->street;
            $Bid->house = $request->house;
            $Bid->flat = $request->flat;
            $Bid->region = $request->region;
            $Bid->who_is_cont_pers1 = $request->who_is_cont_pers1;
            $Bid->first_name_cont_pers1 = $request->first_name_cont_pers1;
            $Bid->last_name_cont_pers1 = $request->last_name_cont_pers1;
            $Bid->phone_cont_pers1 = $request->phone_cont_pers1;
            $Bid->produs = $request->produs;
            $Bid->who_is_cont_pers2 = $request->who_is_cont_pers2;
            $Bid->first_name_cont_pers2 = $request->first_name_cont_pers2;
            $Bid->last_name_cont_pers2 = $request->last_name_cont_per21;
            $Bid->phone_cont_pers2 = $request->phone_cont_pers2;
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

            $message = 'Cerere nouă';
            // ChatMessage::sendNewMessage(null, 'Cerere nouă', $Bid->id);
            Log::addNewLog($request, Log::MODULE_BIDS, Log::OPERATION_ADD, $Bid->id, $message);

            return response()->json([
                'success' => true,
                'data' => [
                    'bid' => $bidRepository->getById($Bid->id),
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
        /* @var $Bid Bid */
        $Bid = Bid::findOrFail($id);
        $Bid->deleted = true;
        $Bid->save();

        $message = 'Ștergere cerere';
        Log::addNewLog($request, Log::MODULE_BIDS, Log::OPERATION_DELETE, $Bid->id, $message);

        return  response()->json([
            'success' => true
        ]);
    }
}
