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
    public function getById(int $id = 0)
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
                DB::raw("DATE_FORMAT(payments.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('payments.deleted')
            ->distinct();

        if ($this->authUser && $this->authUser->role_id === User::USER_ROLE_DEALER && $this->authUser->dealer_id) {
            $items = $items->where('dealer_id', $this->authUser->dealer_id);
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        return $this->standardPagination($items, $pagination);
    }

    /**
     * @param Bid $Bid
     * @return boolean
     */
    public function createPayments(Bid $Bid)
    {
        $BidMonths = BidMonth::whereNull('deleted')
            ->where('bid_id', '=', $Bid->id)
            ->orderBy('date', 'asc')
            ->get();

        if ($BidMonths) {
            /* @var $BidMonth BidMonth */
            foreach ($BidMonths as $BidMonth) {
                $this->create([
                    'bid_id' => $Bid->id,
                    'client_id' => $Bid->client_id,
                    'dealer_id' => $Bid->dealer_id,
                    'user_id' => $Bid->dealer_id,
                    'payment_sum' => $BidMonth->total_per_luna,
                    'date_time' => $BidMonth->date .' 08:00:00',
                    'type' => 1,
                ]);
            }
        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data = [])
    {
        $Payment = new Model();
        $Payment->bid_id = $data['bid_id'] ?? null;
        $Payment->client_id = $data['client_id'] ?? null;
        $Payment->dealer_id = $data['dealer_id'] ?? null;
        $Payment->user_id = $data['user_id'] ?? ($this->authUser->id ?? null);
        $Payment->payment_sum = $data['payment_sum'] ?? null;
        $Payment->date_time = $data['date_time'] ?? null;
        $Payment->type = $data['type'] ?? 1;
        $Payment->pko_number = $data['pko_number'] ?? null;
        $Payment->beznal = $data['beznal'] ?? null;
        $Payment->save();
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id = 0, array $data = [])
    {
        // TODO: Implement update() method.
    }
}
