<?php
use Carbon\Carbon;
?>
@include('pdf.header', $data)
@php
    $hasStandardProduct = false;
    $hasAvanteProduct = false;
    $has1percentProduct = false;
    $has0percentProduct = false;
    $tva = 0;
    $tvaRetailStandard = 0;
    $tvaRetailAvante = 0;
    $tvaRetail1 = 0;
    $tvaRetail0 = 0;
@endphp
@foreach ($data['dealer']['dealer_products'] as $dealer_product)
    @if (isset($dealer_product['product']['type_credits'][0]))
        @foreach ($dealer_product['product']['type_credits'] as $type_credit)
            @if (isset($type_credit['percent_bonus_magazin']) && (double)$type_credit['percent_bonus_magazin'] > (double)$tva)
                @php
                    $tva = $type_credit['percent_bonus_magazin'];
                @endphp
            @endif
            @if ($dealer_product['product_id'] === 1)
                @php
                    $tvaRetailStandard = $type_credit['percent_bonus_magazin'];
                @endphp
            @endif
            @if ($dealer_product['product_id'] === 2)
                @php
                    $tvaRetailAvante = $type_credit['percent_bonus_magazin'];
                @endphp
            @endif
            @if ($dealer_product['product_id'] === 3)
                @php
                    $tvaRetail1 = $type_credit['percent_bonus_magazin'];
                @endphp
            @endif
            @if ($dealer_product['product_id'] === 4)
                @php
                    $tvaRetail0 = $type_credit['percent_bonus_magazin'];
                @endphp
            @endif
        @endforeach
    @endif
    @if ($dealer_product['product_id'] === 1)
        @php
            $hasStandardProduct = true;
        @endphp
    @endif
    @if ($dealer_product['product_id'] === 2)
        @php
            $hasAvanteProduct = true;
        @endphp
    @endif
    @if ($dealer_product['product_id'] === 3)
        @php
            $has1percentProduct = true;
        @endphp
    @endif
    @if ($dealer_product['product_id'] === 4)
        @php
            $has0percentProduct = true;
        @endphp
    @endif
@endforeach
<style>
    @page { margin: 0; }
    body { margin: 30px 30px 20px 60px; }
    body { line-height: 96%; }
</style>

<div id="footer"></div>


<div class="text-center title">
    <span class="strong">
        <span class="title2">
            Acordul aditional nr. 1<br>
            la contractul de colaborare nr. {{$data['dealer']['id']}} din {{ Carbon::parse($data['dealer']['contract_date'])->format('d.m.Y') }}<br>
            între „CREDIT BOX” SRL şi {{$data['dealer']['full_name']}}
        </span>
    </span>
</div>

<div class="text-left title2 mb-15">
    <span class="">mun. Bălți</span>
</div>

<div>
    <div>
        <span class="strong">OCN „CREDIT BOX” SRL</span>, c/f1019602000472 , cu adresa juridică mun. Bălți str. Ștefan cel Mare 57, în persoana Grumeza Sergiu, Director, care activează în baza Statutului, denumită în continuare
        <span class="strong">„Creditor”</span>, pe o parte, şi
        <span class="strong">{{$data['dealer']['full_name']}}</span>, c/f {{$data['dealer']['idno']}},
        cu adresa juridică în {{$data['dealer']['address_jju']}}, în persoana {{$data['dealer']['director_general']}},
        administrator al companiei, care activează în baza Statutului, denumit în continuare <span class="strong">„Beneficiar”</span>
        pe de altă parte, au exprimat intenţia de a semna prezentul acord adițional cu privire la următoarele:
    </div>

    <ol>
        <li>
            În scopul îndeplinirii obligațiilor sale contractuale Creditorul va oferi Clienților Beneficiarului credite nebancare în urmatoarele condiții:
            <br>
            @if($hasStandardProduct)
                <br>Credit: Retail Standart<br>
                <table style="width: 100%; border-collapse: collapse; border: none;" border="1" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr style="height: 20.95pt;">
                        <td style="width: 14.28%; border: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Suma creditului nebancar</span></strong></p>
                        </td>
                        <td style="width: 14.28%; border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Durata creditului nebancar</span></strong></p>
                        </td>
                        <td style="width: 14.28%; border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Dob&icirc;nda anuala</span></strong></p>
                        </td>
                        <td style="width: 14.28%; border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Avans de la client</span></strong></p>
                        </td>
                        <td style="width: 42.84%; border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Taxa aferenta contractului de credit nebancar</span></strong></p>
                        </td>
                    </tr>
                    <tr style="height: 12.65pt;">
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%;"><span style="font-size: 8.0pt; line-height: 100%;">De la Client</span></p>
                        </td>
                    </tr>
                    <tr style="height: 12.65pt;">
                        <td style="border: solid #000000 1px; border-top: none; padding: 0cm 5.4pt 5pt 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">500-50000<br>Oficial angajat</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">6, 8, 10, 12, 18, 24 luni</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">Variaza de la 2,08% pina la 2,16% lunar din soldul initial</span></p>
                        </td>
                    </tr>
                    <tr style="height: 12.65pt;">
                        <td style="border: solid #000000 1px; border-top: none; padding: 0cm 5.4pt 5pt 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">500-25000<br>Neoficial angajat</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">6, 8, 10, 12, 18, 24 luni</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">Variaza de la 1,93% pina la 1,95% lunar din soldul initial</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            @endif

            @if($hasAvanteProduct || $has1percentProduct || $has0percentProduct)
                <br>Credit promoțional:
                @if($hasAvanteProduct)
                    Retail Avante
                    @if($has1percentProduct || $has0percentProduct)
                        ,
                    @endif
                @endif
                @if($has1percentProduct)
                    Retail 1%
                    @if($has0percentProduct)
                        ,
                    @endif
                @endif
                @if($has0percentProduct)
                    Retail 0%
                @endif
                <br>
                <table  style="width: 100%; border-collapse: collapse; border: none;" border="1" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr style="height: 24.45pt;">
                        <td style="width: 14.28%; border: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p  style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Suma creditului nebancar</span></strong></p>
                        </td>
                        <td style="width: 14.28%; border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Durata creditului nebancar</span></strong></p>
                        </td>
                        <td style="width: 14.28%; border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Dob&icirc;nda anuala</span></strong></p>
                        </td>
                        <td style="width: 14.28%;border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;" rowspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Avans de la client</span></strong></p>
                        </td>
                        <td style="width: 42.84%;border: solid #000000 1px; border-left: none; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2">
                            <p style="text-align: center; line-height: 100%;"><strong><span style="font-size: 8.0pt; line-height: 100%;">Taxa aferenta contractului de credit nebancar</span></strong></p>
                        </td>
                    </tr>
                    <tr style="height: 14.75pt;">
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%;"><span style="font-size: 8.0pt; line-height: 100%;">De la Client</span></p>
                        </td>
                        <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                            <p style="text-align: center; line-height: 100%;"><span style="font-size: 8.0pt; line-height: 100%;">De la Beneficiar</span></p>
                        </td>
                    </tr>
                    @if($hasAvanteProduct)
                        <tr style="height: 15.75pt;">
                            <td style="border: solid #000000 1px; border-top: none; padding: 0cm 5.4pt 5pt 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">500-50000<br>Oficial angajat</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">6, 8, 10, 12, 18, 24 luni</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">1,9% lunar din soldul initial</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                        </tr>
                    @endif

                    @if($has1percentProduct)
                        <tr style="height: 15.75pt;">
                            <td style="border: solid #000000 1px; border-top: none; padding: 0cm 5.4pt 5pt 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">500-50000<br>Oficial angajat</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">6, 8, 10, 12 luni</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">1% lunar din soldul initial</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">6 luni- 6%,&nbsp; 8 luni-8%, </span></p>
                                <p style="line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">10 luni- 10%, </span></p>
                                <p style="line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">12 luni &ndash; 12%, taxa unica</span></p>
                            </td>
                        </tr>
                    @endif
                    @if($has0percentProduct)
                        <tr style="height: 15.75pt;">
                            <td style="border: solid #000000 1px; border-top: none; padding: 0cm 5.4pt 5pt 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">500-50000<br>Oficial angajat</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">4, 6, 8, 10, 12 luni</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">0%</span></p>
                            </td>
                            <td style="border-top: none; border-left: none; border-bottom: solid #000000 1px; border-right: solid #000000 1px; padding: 0cm 5.4pt 0cm 5.4pt;">
                                <p style="text-align: center; line-height: 100%; margin: 0cm 0cm 0.0001pt; font-size: 9pt; "><span style="font-size: 8.0pt; line-height: 100%;">8%, 11%, 14%, 17%, 19% taxa unica</span></p>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            @endif
        </li>
        @if ($tvaRetailStandard > 0 || $tvaRetailAvante > 0 || $tvaRetail1 > 0 || $tvaRetail0 > 0)
            <li>
                În scopul stimulării promovării serviciiilor de creditare nebancara ale Creditorului clienților Beneficiarului,
                Creditorul va achita Benerficiarului
                @if ($tvaRetailStandard > 0)
                    un discount care reprezinta <strong>{{$tvaRetailStandard}}% (cu TVA)</strong> din valoarea <strong>creditului Retail Standart</strong>
                @endif
                @if ($tvaRetailStandard > 0 && ($tvaRetailAvante > 0 || $tvaRetail1 > 0 || $tvaRetail0 > 0))
                    și
                @endif
                @if ($tvaRetailAvante > 0)
                    un discount care reprezinta <strong>{{$tvaRetailAvante}}% (cu TVA)</strong> din valoarea <strong>creditului Retail Avante</strong>
                @endif
                @if (($tvaRetailStandard > 0 || $tvaRetailAvante > 0) && ($tvaRetail1 > 0 || $tvaRetail0 > 0))
                    și
                @endif
                @if ($tvaRetail1 > 0)
                    un discount care reprezinta <strong>{{$tvaRetail1}}% (cu TVA)</strong> din valoarea <strong>creditului Retail 1%</strong>
                @endif
                @if (($tvaRetailStandard > 0 || $tvaRetailAvante > 0 || $tvaRetail1) && $tvaRetail0 > 0)
                    și
                @endif
                @if ($tvaRetail0 > 0)
                    un discount care reprezinta <strong>{{$tvaRetail0}}% (cu TVA)</strong> din valoarea <strong>creditului Retail 0%</strong>
                @endif
                eliberat de catre Creditor Clientului cu asistarea Beneficiarului.
            </li>
            <li>
                Plata discountului devine exigibila de îndata ce Creditorul a transferat suma creditului în baza contractului de credit nebancar încheiat prin intermediul/cu asistarea Beneficiarului.
            </li>
            <li>
                Plata discountului va fi făcuta lunar, prin transfer în contul Beneficiarului, nu mai târziu de data de 15 a lunii următoare celei de referinţă.
            </li>
        @endif
        <li>
            Creditorul va furniza lunar Beneficiarului informaţia despre contractele de credit nebancar semnate şi executate în partea ce ţine de acordarea creditelor.
        </li>
        <li>
            Celalte puncte ale Contractului rămân neschimbate.
        </li>
        <li>
            Prezentul acord este încheiat în limba română, în două exemplare, câte una pentru fiecare parte, ambele având aceeași putere juridică, fiind parte integrantă a contractului de colaborare.
        </li>
        <li>
            Prezentul acord intră în vigoare la data semnării lui de către părți și produce efecte juridice din momentul semnării.
        </li>
    </ol>

    <table style="width: 100%; border: none;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 13px;">
                    <div class="title-contract">
                        <span class="title2"><span class="strong">Beneficiar</span></span>
                    </div>
                    {{$data['dealer']['full_name']}}<br>
                    Director {{$data['dealer']['director_general']}} _________________________
                </td>
                <td style="vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 13px;">
                    <div class="title-contract">
                        <span class="title2"><span class="strong">Creditor</span></span>
                    </div>
                    OCN „CREDIT BOX” SRL<br>
                    Director Grumeza Sergiu ______________________
                    <div style="position: relative;" >
                        <div style="position: absolute;z-index: 999999;top: -110px; left: 50px;" class="stampila-semnatura"></div>
                    </div>
                </td>
            </tr>
    </table>
</div>
@include('pdf.footer', $data)

