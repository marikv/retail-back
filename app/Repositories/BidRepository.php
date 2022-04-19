<?php

namespace App\Repositories;

use App\Models\Bid as Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidRepository extends AbstractCoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }
    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id = 0): mixed
    {
        return $this->startConditions()
            ->where('id', '=', $id)
            ->with('client')
            ->with('type_credit')
            ->with('dealer')
            ->with('user')
            ->with('execute_user')
            ->with('bid_months')
            ->with('files')
            ->first();
    }

    /**
     * @param $filter
     * @param array|null $pagination
     * @param string $activeModule
     * @return LengthAwarePaginator
     */
    public function list($filter, array $pagination = null, string $activeModule = ''): LengthAwarePaginator
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
            ->leftJoin('users', 'users.id', '=', 'bids.user_id')
            ->leftJoin('dealers', 'dealers.id', '=', 'bids.dealer_id')
            ->leftJoin('clients', 'clients.id', '=', 'bids.client_id')
            ->leftJoin('type_credits', 'type_credits.id', '=', 'bids.type_credit_id')
            ->whereNull('bids.deleted')
            ->distinct();

        if ($activeModule === 'Contracts') {
            $items = $items->where('bids.status_id', '=', Model::BID_STATUS_SIGNED_CONTRACT);
            if (Auth::user()->role_id === User::USER_ROLE_DEALER) {
                $items = $items->where('bids.dealer_id', '=', Auth::user()->dealer_id);
            }
        } else {
            $items = $items
                ->where('bids.status_id', '!=', Model::BID_STATUS_SIGNED_CONTRACT)
                ->where(function ($items) {
                    $items->where(function ($items) {
                        $day = Carbon::parse(date('Y-m-d'))->modify('-2 days')->format('Y-m-d');
                        $items->where('bids.created_at', '>', $day . ' 00:00:00')
                            ->where('bids.status_id', '=', Model::BID_STATUS_REFUSED);
                    })
                        ->orWhere('bids.status_id', '!=', Model::BID_STATUS_REFUSED);
                });

            if (Auth::user()->role_id === User::USER_ROLE_DEALER) {
                $items = $items->where('bids.user_id', '=', Auth::user()->id)
                    ->where('bids.dealer_id', '=', Auth::user()->dealer_id);
            }
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        return $this->standardPagination($items, $pagination);
    }
}
