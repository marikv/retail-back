<?php

namespace App\Repositories;

use App\Models\Bid;
use App\Models\Bid as Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidRepository extends AbstractCoreRepository
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
            ->with('client')
            ->with('type_credit')
            ->with('dealer')
            ->with('user')
            ->with('execute_user')
            ->with('bid_months')
            ->with('bid_scorings')
            ->with('files')
            ->first();
    }
    /**
     * @param Bid|null $Bid
     * @return string
     */
    public function getAddress(Bid $Bid = null): string
    {
        $clientAddressArray = [];
        if ($Bid) {
            $Bid->region = trim($Bid->region);
            $Bid->region = str_replace('m.', '', $Bid->region);
            $Bid->region = str_replace('r.', '', $Bid->region);
            $Bid->region = str_replace('raion', '', $Bid->region);
            $Bid->region = trim($Bid->region);
            $Bid->localitate = trim($Bid->localitate);
            $Bid->localitate = str_replace('m.', '', $Bid->localitate);
            $Bid->localitate = str_replace('r.', '', $Bid->localitate);
            $Bid->localitate = str_replace('raion', '', $Bid->localitate);
            $Bid->localitate = trim($Bid->localitate);

            $municipii = ['balti', 'bălți', 'balți', 'bălti', 'cisinau', 'chisinau', 'chișinău', 'chisinău', 'chișinau'];

            if (in_array(strtolower($Bid->region), $municipii) && in_array(strtolower($Bid->localitate), $municipii)) {
                $Bid->localitate = null;
            }

            if ($Bid->region) {
                if (in_array(strtolower($Bid->region), $municipii)) {
                    $clientAddressArray[] = 'mun.' . ucfirst($Bid->region);
                } else {
                    $clientAddressArray[] = 'r.' . ucfirst($Bid->region);
                }
            }

            if ($Bid->localitate) {
                $clientAddressArray[] =  ucfirst($Bid->localitate);
            }
            if ($Bid->street) {
                $str = ['str ', 'strada', 'str.'];
                foreach ($str as $s) {
                    $Bid->street = str_replace($s, '', $Bid->street);
                }
                $clientAddressArray[] =  'str. '. ucfirst($Bid->street);
            }
            if ($Bid->house) {
                $clientAddressArray[] =  $Bid->house;
            }
            if ($Bid->flat) {
                $clientAddressArray[] =  $Bid->flat;
            }
        }
        return implode(', ', $clientAddressArray);
    }

    /**
     * @param string|null $filter
     * @param array|null $pagination
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function list(string $filter = null, array $pagination = null, array $options = []): LengthAwarePaginator
    {
        $activeModule = $options['activeModule'] ?? '';

        $items = DB::table('bids')
            ->select([
                'bids.*',
                'dealers.logo',
                'dealers.name as dealer_name',
                'users.name as user_name',
                'type_credits.name as type_credits_name',
                'clients.last_name as client_last_name',
                'clients.first_name as client_first_name',
                DB::raw("DATE_FORMAT(bids.created_at, '%d.%m.%Y %H:%i') as created_at2"),
            ])
            ->leftJoin('users', 'users.id', '=', 'bids.user_id')
            ->leftJoin('dealers', 'dealers.id', '=', 'bids.dealer_id')
            ->leftJoin('clients', 'clients.id', '=', 'bids.client_id')
            ->leftJoin('type_credits', 'type_credits.id', '=', 'bids.type_credit_id')
            ->whereNull('bids.deleted')
            ->distinct();

        if ($activeModule === 'Contracts') {
            $items = $items->where('bids.status_id', '=', Model::BID_STATUS_SIGNED_CONTRACT);
            if ($this->authUser->role_id === User::USER_ROLE_DEALER) {
                $items = $items->where('bids.dealer_id', '=', $this->authUser->dealer_id);
            }
        } else {
            $items = $items
                ->where('bids.status_id', '!=', Model::BID_STATUS_SIGNED_CONTRACT)
                ->where(function ($items) {
                    $items->where(function ($items) {
                        $day = Carbon::parse(date('Y-m-d'))->modify('-2 days')->format('Y-m-d');
                        $items->where('bids.created_at', '>', $day . ' 00:00:00')
                            ->where('bids.status_id', '=', Model::BID_STATUS_REFUSED);
                    })
                        ->orWhere('bids.status_id', '!=', Model::BID_STATUS_REFUSED);
                });

            if ($this->authUser->role_id === User::USER_ROLE_DEALER) {
                $items = $items->where('bids.user_id', '=', $this->authUser->id)
                    ->where('bids.dealer_id', '=', $this->authUser->dealer_id);
            }
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
