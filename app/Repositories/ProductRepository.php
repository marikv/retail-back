<?php

namespace App\Repositories;

use App\Models\DealerProduct;
use App\Models\Product as Model;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRepository extends AbstractCoreRepository
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

//
//    /**
//     * @param int $id
//     * @return mixed
//     */
//    public function delete(int $id = 0): mixed
//    {
//        $model = $this->model::findOrFail($id);
//        $model->deleted = true;
//        return $model->save();
//    }

    /**
     * @param $filter
     * @param array|null $pagination
     * @param string $activeModule
     * @return LengthAwarePaginator
     */
    public function list($filter, array $pagination = null, string $activeModule = ''): LengthAwarePaginator
    {
        $items = DB::table('products')
            ->select([
                'products.*',
                DB::raw("DATE_FORMAT(products.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('products.deleted')
            ->distinct();

        if ($filter) {
            $items = $items->where('products.name', 'like', $filter . '%');
        }

        if (Auth::user()->role_id === User::USER_ROLE_DEALER && Auth::user()->dealer_id) {
            $DealerProducts = DealerProduct::whereNull('deleted')
                ->where('dealer_id', '=', Auth::user()->dealer_id)
                ->get();
            $DealerProductsArray = $DealerProducts->map(function ($p) { return $p->product_id; })->toArray();
            $items = $items->whereIn('id', $DealerProductsArray);
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        return $this->standardPagination($items, $pagination);
    }
}
