<?php

namespace App\Repositories;

use App\Models\User as Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository extends AbstractCoreRepository
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
        $Users = DB::table('users')
            ->select([
                'users.*',
                DB::raw("DATE_FORMAT(users.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->whereNull('users.deleted')
            ->distinct();

        if (!empty($filter)) {
            $Users = $Users
                ->where('users.name', 'like', $filter . '%')
                ->orWhere('users.phone1', 'like', $filter . '%')
                ->orWhere('users.email', 'like', $filter . '%')
            ;
        }

        $Users = $this->standardOrderBy($Users, $pagination, 'id', 'desc');
        return $this->standardPagination($Users, $pagination);
    }
}
