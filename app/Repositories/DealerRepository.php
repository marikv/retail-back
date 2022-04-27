<?php

namespace App\Repositories;

use App\Models\Dealer as Model;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DealerRepository extends AbstractCoreRepository
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
            ->with('dealer_products')
            ->with('dealer_products.product')
            ->with('dealer_products.product.type_credits')
            ->first()
            ->toArray();
    }

    /**
     * @param $filter
     * @param array|null $pagination
     */
    public function list($filter, array $pagination = null)
    {
        $items = DB::table('dealers')
            ->select([
                'dealers.*',
                DB::raw("DATE_FORMAT(dealers.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('dealers.deleted')
            ->distinct();
        if (!empty($filter)) {
            $items = $items
                ->where('dealers.name', 'like', $filter . '%')
                ->orWhere('dealers.full_name', 'like', $filter . '%')
                ->orWhere('dealers.phone1', 'like', $filter . '%')
                ->orWhere('dealers.phone2', 'like', $filter . '%')
                ->orWhere('dealers.email', 'like', $filter . '%')
                ->orWhere('dealers.description', 'like', $filter . '%')
            ;
        }

        if (Auth::user()->role_id === User::USER_ROLE_DEALER) {
            $items = $items->where('dealers.id', '=', Auth::user()->dealer_id);
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        $items = $this->standardPagination($items, $pagination);
        return $items;

//        $rows = $items->items();
//        $tmp = [];
//        foreach ($items as $k=>$v) {
//            $tmp[$k] = (array)$v;
//            $tmp[$k]['model'] = $this->getById($tmp[$k]['id']);
//        }
//        $items = $this->forcePagination($items, $tmp);
//        return $items;
    }
}
