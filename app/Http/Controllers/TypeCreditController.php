<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Dealer;
use App\Models\TypeCredit;
use App\Models\User;
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
    public function getDataById($id, Request $request): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => TypeCredit::findOrFail($id),
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
        if (Auth::user()->role_id === User::USER_ROLE_ADMIN) {
            if ((int)$id > 0) {
                $TypeCredit = TypeCredit::findOrFail($id);
            } else {
                $TypeCredit = new TypeCredit();
            }
            $TypeCredit->name = $request->name;
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
            $TypeCredit->save();
        }

        return response()->json([
            'success' => true,
            'data' => []
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function typeCreditsList(Request $request): JsonResponse
    {
        $dealers = DB::table('type_credits')
            ->select([
                'type_credits.*',
                DB::raw("DATE_FORMAT(type_credits.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('type_credits.deleted')
            ->distinct();
        if ($request->filter) {
            $dealers = $dealers
                ->where('type_credits.name', 'like', $request->filter . '%')
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
     * @param Request $request
     * @return JsonResponse
     */
    public function delete($id, Request $request): JsonResponse
    {
        if (Auth::user()->role_id === User::USER_ROLE_ADMIN) {

            $TypeCredit = TypeCredit::findOrFail($id);
            $TypeCredit->deleted = true;
            $TypeCredit->save();
        }

        return  response()->json([
            'success' => true
        ], 200);
    }
}
