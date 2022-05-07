<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Repositories\BidRepository;
use App\Repositories\DealerRepository;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{


    public function contractDealer(Dealer $dealer, Request $request)
    {
        $data = [];
        $data['data'] = [
            'dealer' => $dealer
        ];

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.contractDealer', $data, true)->stream();
    }

    public function contractDealerAcord(Dealer $dealer, Request $request, DealerRepository $dealerRepository)
    {
        $data = [];
        $data['data'] = [
            'dealer' => $dealerRepository->getById($dealer->id)
        ];

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.contractDealerAcord', $data, true)->stream();
    }

    public function contractDealerConsimtamant(Dealer $dealer, Request $request, DealerRepository $dealerRepository)
    {
        $data = [];
        $data['data'] = [
            'dealer' => $dealerRepository->getById($dealer->id)
        ];

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.contractDealerConsimtamant', $data, true)->stream();
    }


    public function contract(int $bidId, Request $request, BidRepository $bidRepository, DealerRepository $dealerRepository)
    {
        $data = [];
        $bid = $bidRepository->getById($bidId);
        $dealer = $dealerRepository->getById($bid->dealer_id);

        $data['data'] = [
            'bid' => $bid,
            'dealer' => $dealer,
        ];
        $data['data']['bid']['address'] = $bidRepository->getAddress($bid);
        $data['data']['dae-formula_base64'] = base64_encode(file_get_contents(public_path('img/dae-formula.jpg')));

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.contractClient', $data, true)->stream();
    }

    public function anexa(int $bidId, Request $request, BidRepository $bidRepository, DealerRepository $dealerRepository)
    {
        $data = [];
        $bid = $bidRepository->getById($bidId);
        $dealer = $dealerRepository->getById($bid->dealer_id);

        $data['data'] = [
            'bid' => $bid,
            'dealer' => $dealer,
        ];
        $data['data']['bid']['address'] = $bidRepository->getAddress($bid);
        $data['data']['dae-formula_base64'] = base64_encode(file_get_contents(public_path('img/dae-formula.jpg')));

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.anexaClient', $data, true)->stream();
    }


    public function preContract(int $id, Request $request)
    {
        $data = [];
        $data['data'] = [];

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.preContract', $data, true)->stream();
    }


    private static function setHeaderData(array $data): array
    {
        $data['data']['logo_base64'] = base64_encode(file_get_contents(public_path('img/logo.png')));
        $data['data']['stampila-semnatura'] = base64_encode(file_get_contents(public_path('img/stampila-semnatura.png')));
        return $data;
    }


    private static function getPDF($file, $data, $isPortrait = true)
    {
        $pdf = PDF::loadView($file, $data)->setPaper('a4', $isPortrait ? 'portrait' : 'landscape');
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf;
    }
}
