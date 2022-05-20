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
     * @param string|null $filter
     * @param array|null $pagination
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function list(string $filter = null, array $pagination = null, array $options = []): LengthAwarePaginator
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
        if ($this->authUser->role_id === Model::USER_ROLE_DEALER) {
            $Users = $Users
                ->where('users.role_id', '=', Model::USER_ROLE_DEALER)
                ->where('users.dealer_id', '=', $this->authUser->dealer_id);
        } else {
            $Users = $Users->where('users.role_id', '!=', Model::USER_ROLE_DEALER);
        }

        $Users = $this->standardOrderBy($Users, $pagination, 'id', 'desc');
        return $this->standardPagination($Users, $pagination);
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
