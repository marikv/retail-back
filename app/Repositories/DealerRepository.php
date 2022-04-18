<?php

namespace App\Repositories;

use App\Models\Dealer as Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @param $id
     * @return mixed
     */
    public function getDealer($id): mixed
    {
        return $this->startConditions()
            ->where('id', '=', $id)
            ->with('dealer_type_credits')
            ->first()
            ->toArray();
    }

    /**
     * @param $filter
     * @param array|null $pagination
     * @return LengthAwarePaginator
     */
    public function list($filter, array $pagination = null): LengthAwarePaginator
    {
        $dealers = DB::table('dealers')
            ->select([
                'dealers.*',
                DB::raw("DATE_FORMAT(dealers.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('dealers.deleted')
            ->distinct();
        if (!empty($filter)) {
            $dealers = $dealers
                ->where('dealers.name', 'like', $filter . '%')
                ->orWhere('dealers.full_name', 'like', $filter . '%')
                ->orWhere('dealers.phone1', 'like', $filter . '%')
                ->orWhere('dealers.phone2', 'like', $filter . '%')
                ->orWhere('dealers.email', 'like', $filter . '%')
                ->orWhere('dealers.description', 'like', $filter . '%')
            ;
        }

        $dealers = $this->standardOrderBy($dealers, $pagination, 'id', 'desc');
        return $this->standardPagination($dealers, $pagination);
    }
}
