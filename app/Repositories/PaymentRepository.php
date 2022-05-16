<?php

namespace App\Repositories;

use App\Models\Bid;
use App\Models\BidMonth;
use App\Models\Payment as Model;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaymentRepository extends AbstractCoreRepository
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
        return $this->model::findOrFail($id);
    }

    /**
     * @param string|null $filter
     * @param array|null $pagination
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function list(string $filter = null, array $pagination = null, array $options = []): LengthAwarePaginator
    {
        $items = DB::table('payments')
            ->select([
                'payments.*',
                'dealers.logo',
                'dealers.name as dealer_name',
                'users.name as user_name',
                'clients.last_name as client_last_name',
                'clients.first_name as client_first_name',
                DB::raw("DATE_FORMAT(payments.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->leftJoin('users', 'users.id', '=', 'payments.user_id')
            ->leftJoin('bids', 'bids.id', '=', 'payments.bid_id')
            ->leftJoin('dealers', 'dealers.id', '=', 'payments.dealer_id')
            ->leftJoin('clients', 'clients.id', '=', 'payments.client_id')
            ->whereNull('payments.deleted')
            ->whereNull('dealers.deleted')
            ->whereNull('bids.deleted')
            ->distinct();

        if (!empty($options['contractNumber'])) {
            $items = $items->where('bid_id', '=', $options['contractNumber']);
        }
        if (!empty($filter)) {
            $items = $items->where('dealers.name', 'like', $filter.'%');
        }
        if ($this->authUser && $this->authUser->role_id === User::USER_ROLE_DEALER && $this->authUser->dealer_id) {
            $items = $items->where('dealer_id', '=', $this->authUser->dealer_id);
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        return $this->standardPagination($items, $pagination);
    }

    /**
     * @param Bid $Bid
     * @return boolean
     */
    public function createPayments(Bid $Bid): bool
    {
        $BidMonths = BidMonth::whereNull('deleted')
            ->where('bid_id', '=', $Bid->id)
            ->orderBy('date', 'asc')
            ->get();

        if ($BidMonths) {
            /* @var $BidMonth BidMonth */
            foreach ($BidMonths as $BidMonth) {
                $Payment = $this->create([
                    'bid_id' => $Bid->id ?? null,
                    'client_id' => $Bid->client_id ?? null,
                    'dealer_id' => $Bid->dealer_id ?? null,
                    'user_id' => $Bid->dealer_id ?? null,
                    'payment_sum' => $BidMonth->total_per_luna ?? null,
                    'date_time' => $BidMonth->date .' 08:00:00',
                    'type' => 1,
                ]);
            }
        }
        return true;
    }

}
