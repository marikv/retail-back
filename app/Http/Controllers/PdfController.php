<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{


    public function contractDealer(Dealer $dealer, Request $request)
    {
        $data = [];
        $data['data'] = [];

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.contractDealer', $data, true)->stream();
    }


    public function contract(int $bidId, Request $request)
    {
        $data = [];
        $data['data'] = [];

        $data = self::setHeaderData($data);

        return self::getPDF('pdf.contract', $data, true)->stream();
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
        return $data;
    }


    private static function getPDF($file, $data, $isPortrait = true)
    {
        $pdf = PDF::loadView($file, $data)->setPaper('a4', $isPortrait ? 'portrait' : 'landscape');
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf;
    }
}