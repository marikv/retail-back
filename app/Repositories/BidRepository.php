<?php

namespace App\Repositories;

use App\Models\Bid;
use App\Models\Bid as Model;
use App\Models\BidMonth;
use App\Models\ChatMessage;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
            $Bid->region_reg = trim($Bid->region_reg);
            $Bid->region_reg = str_replace('m.', '', $Bid->region_reg);
            $Bid->region_reg = str_replace('r.', '', $Bid->region_reg);
            $Bid->region_reg = str_replace('raion', '', $Bid->region_reg);
            $Bid->region_reg = trim($Bid->region_reg);
            $Bid->localitate_reg = trim($Bid->localitate_reg);
            $Bid->localitate_reg = str_replace('m.', '', $Bid->localitate_reg);
            $Bid->localitate_reg = str_replace('r.', '', $Bid->localitate_reg);
            $Bid->localitate_reg = str_replace('raion', '', $Bid->localitate_reg);
            $Bid->localitate_reg = trim($Bid->localitate_reg);

            $municipii = ['balti', 'bălți', 'balți', 'bălti', 'cisinau', 'chisinau', 'chișinău', 'chisinău', 'chișinau'];

            if (in_array(strtolower($Bid->region_reg), $municipii) && in_array(strtolower($Bid->localitate_reg), $municipii)) {
                $Bid->localitate_reg = null;
            }

            if ($Bid->region_reg) {
                if (in_array(strtolower($Bid->region_reg), $municipii)) {
                    $clientAddressArray[] = 'mun.' . ucfirst($Bid->region_reg);
                } else {
                    $clientAddressArray[] = 'r.' . ucfirst($Bid->region_reg);
                }
            }

            if ($Bid->localitate_reg) {
                $clientAddressArray[] =  ucfirst($Bid->localitate_reg);
            }
            if ($Bid->street_reg) {
                $str = ['str ', 'strada', 'str.'];
                foreach ($str as $s) {
                    $Bid->street_reg = str_replace($s, '', $Bid->street_reg);
                }
                $clientAddressArray[] =  'str. '. ucfirst($Bid->street_reg);
            }
            if ($Bid->house_reg) {
                $clientAddressArray[] =  $Bid->house_reg;
            }
            if ($Bid->flat_reg) {
                $clientAddressArray[] =  $Bid->flat_reg;
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
        $dealer_id = $options['dealer_id'] ?? null;

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

        if ($dealer_id) {
            $items = $items->where('bids.dealer_id', '=', $dealer_id);
        }

        if ($activeModule === 'Contracts') {
            $items = $items->whereIn('bids.status_id', [Model::BID_STATUS_CONTRACT_SIGNED, Model::BID_STATUS_CONTRACT_PAYED]);
            if ($this->authUser->role_id === User::USER_ROLE_DEALER) {
                $items = $items->where('bids.dealer_id', '=', $this->authUser->dealer_id);
            }
        } else {
            $items = $items
                ->whereNotIn('bids.status_id',  [Model::BID_STATUS_CONTRACT_SIGNED, Model::BID_STATUS_CONTRACT_PAYED])
                ->where(function ($items) {
                    $items->where(function ($items) {
                        $day = Carbon::parse(date('Y-m-d'))->modify('-7 days')->format('Y-m-d');
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
        if (!empty($filter)) {
            $items = $items
                ->where('clients.last_name', 'like', $filter . '%')
                ->orWhere('clients.first_name', 'like', $filter . '%')
                ->orWhere('clients.phone1', 'like', $filter . '%')
                ->orWhere('clients.phone2', 'like', $filter . '%')
                ->orWhere('clients.email', 'like', $filter . '%')
            ;
        }

        $items = $this->standardOrderBy($items, $pagination, 'id', 'desc');
        return $this->standardPagination($items, $pagination);
    }

    public function delete(int $id = 0): bool
    {
        $BidMonths = BidMonth::whereNull('deleted')
            ->where('bid_id', '=', $id)
            ->get();
        $bidMonthRepository = new BidMonthRepository();
        foreach ($BidMonths as $bidMonth) {
            $bidMonthRepository->delete($bidMonth->id);
        }

        $Payments = Payment::whereNull('deleted')
            ->where('bid_id', '=', $id)
            ->get();
        $PaymentRepository = new PaymentRepository();
        foreach ($Payments as $Payment) {
            $PaymentRepository->delete($Payment->id);
        }

        $ChatMessages = ChatMessage::whereNull('deleted')
            ->where('bid_id', '=', $id)
            ->get();
        $ChatMessageRepository = new ChatMessageRepository();
        foreach ($ChatMessages as $ChatMessage) {
            $ChatMessageRepository->delete($ChatMessage->id);
        }

        return parent::delete($id);
    }
}
