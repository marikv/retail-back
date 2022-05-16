<?php

namespace App\Repositories;

use App\Models\Client as Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientRepository extends AbstractCoreRepository
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
            ->with('bids')
            ->with('files')
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
        $items = DB::table('clients')
            ->select([
                'clients.*',
                DB::raw("DATE_FORMAT(clients.birth_date, '%d.%m.%Y') as birth_date2"),
                DB::raw("DATE_FORMAT(clients.buletin_date_till, '%d.%m.%Y') as buletin_date_till2"),
                DB::raw("DATE_FORMAT(clients.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('clients.deleted')
            ->distinct();

        if (!empty($options['column']) && !empty($filter)) {
            $items = $items->where('clients.'.$options['column'], '=', $filter);
        }
        elseif (!empty($filter)) {
            $items = $items
                ->where('clients.last_name', 'like', $filter . '%')
                ->orWhere('clients.first_name', 'like', $filter . '%')
                ->orWhere('clients.phone1', 'like', $filter . '%')
                ->orWhere('clients.phone2', 'like', $filter . '%')
                ->orWhere('clients.email', 'like', $filter . '%')
                ->orWhere('clients.buletin_idnp', 'like', $filter . '%')
                ->orWhere('clients.buletin_sn', 'like', $filter . '%')
            ;
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
