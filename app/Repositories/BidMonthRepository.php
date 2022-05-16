<?php

namespace App\Repositories;

use App\Models\BidMonth;
use App\Models\BidMonth as Model;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BidMonthRepository extends AbstractCoreRepository
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
            ->with('bid')
            ->first();
    }

    /**
     * @param string|null $filter
     * @param array|null $pagination
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function list(string $filter = null, array $pagination = null, array $options = []): LengthAwarePaginator
    {
        $items = DB::table('bid_months')
            ->select([
                'bid_months.*',
                DB::raw("DATE_FORMAT(bid_months.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('bid_months.deleted')
            ->distinct();

        if (!empty($options['column']) && !empty($filter)) {
            $items = $items->where('bid_months.'.$options['column'], '=', $filter);
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        return $this->standardPagination($items, $pagination);
    }

}
