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

    /**
     * @param string|null $filter
     * @param array|null $pagination
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function list(string $filter = null, array $pagination = null, array $options = []): LengthAwarePaginator
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

        if ($this->authUser->role_id === User::USER_ROLE_DEALER && $this->authUser->dealer_id) {
            $DealerProducts = DealerProduct::whereNull('deleted')
                ->where('dealer_id', '=', $this->authUser->dealer_id)
                ->get();
            $DealerProductsArray = $DealerProducts->map(function ($p) { return $p->product_id; })->toArray();
            $items = $items->whereIn('id', $DealerProductsArray);
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        return $this->standardPagination($items, $pagination);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data = [])
    {
        // TODO: Implement create() method.
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
