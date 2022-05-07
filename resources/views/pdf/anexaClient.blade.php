<?php
use Carbon\Carbon;
?>
@include('pdf.header', $data)
<style>
    @page { margin: 0; }
    body {
        margin: 60px 60px 120px 85px;
        font-size: 10px;
        line-height: 1;
    }
    td {
        vertical-align: top;
        padding-top: 3px;
        padding-bottom: 3px;
    }
</style>

{{--<div id="footer">--}}
{{--    <table style="width: 100%; border: none;color: #626262" cellspacing="0" cellpadding="0">--}}
{{--        <tr>--}}
{{--            <td style="font-style:italic;text-align: center;vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 12px;">--}}
{{--                Creditor ______________________--}}
{{--            </td>--}}
{{--            <td style="font-style:italic;text-align: center;vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 12px;">--}}
{{--                Client ______________________--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--    </table>--}}
{{--</div>--}}


<div class="text-center title">
    <span class="strong">ANEXA № 1</span>
</div>
<div class="text-center title">
    <span class="strong">LA CONTRACT DE CREDIT NEBANCAR № {{$data['bid']['id']}}</span>
</div>
<div class="text-center">
    <span class="strong">1.	Graficul de rambursare a plaților conform Contractului de credit nebancar :</span>
</div>

{{--<table border="0" cellpadding="0" cellspacing="0" style="border: none;width: 100%;">--}}
{{--    <tr>--}}
{{--        <td style="width: 30%;">--}}
{{--            <div class="text-left title2">--}}
{{--                <span class="strong">mun. Bălți</span>--}}
{{--            </div>--}}
{{--        </td>--}}
{{--        <td style=""> &nbsp; </td>--}}
{{--        <td class="text-center" style="width: 170px;border-bottom: 1px solid #000000;height: 9px;">{{ Carbon::parse($data['bid']['bid_date'])->format('d.m.Y') }}</td>--}}
{{--    </tr>--}}
{{--    <tr style="height: 9px;">--}}
{{--        <td style="height: 9px;"> &nbsp; </td>--}}
{{--        <td style="height: 9px;"> &nbsp; </td>--}}
{{--        <td class="text-center" style="vertical-align: top; font-size: 11px;height: 9px;">(data încheierii contractului)</td>--}}
{{--    </tr>--}}
{{--</table>--}}

<table border="1" cellpadding="0" cellspacing="0" style="border: 1px solid #000000;width: 100%;">
    <tr>
        <td colspan="2" class="text-left title2 ba pl-10">
            <span class="strong">1. Numele/denumirea şi datele de contact ale Creditorului</span>
        </td>
    </tr>
    <tr>
        <td class="w-50 br bb bl pl-10">
            Creditor<br>
            Adresă<br>
            Telefon pentru informații<br>
            Adresa de e-mail*<br>
            Reprezentata de
        </td>
        <td class="w-50 br bb pl-10">
            <span class="strong">OCN „CREDIT BOX” SRL</span><br>
            mun. Bălți, str. Ștefan cel Mare,57<br>
            c/f 1019602000472<br>
            BC VICTORIABANK SA, sucursala 1, Bălți, c.b.VICBMD2X740<br>
            IBAN MD65VI022510100000028MDL<br>
            023185858<br>
            creditboxmd@gmail.com
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-left title2 bl bb br pl-10">
            <span class="strong">2. Numele/denumirea şi datele de contact al Clientului</span>
        </td>
    </tr>
    <tr>
        <td class="w-50 br bb bl pl-10">
            Numele Prenumele Clientului<br>
            Data, luna și anul nașterii<br>
            Codul personal<br>
            Act de identitate Seria și numărul<br>
            Eliberat de:<br>
            Adresă<br>
            Nr. telefon<br>
            Adresa de e-mail
        </td>
        <td class="w-50 br bb pl-10">
            <span class="strong">
            {{($data['bid']['last_name'] ?? '')}} {{($data['bid']['first_name'] ?? '')}} {{($data['bid']['patronymic'] ?? '')}}<br>
            {{Carbon::parse($data['bid']['birth_date'])->format('d.m.Y')}}<br>
            {{$data['bid']['idnp']}}<br>
            {{$data['bid']['buletin_sn']}}<br>
            {{$data['bid']['buletin_office']}}<br>
            {{$data['bid']['address']}}<br>
            {{($data['bid']['phone1'] ?? '')}}<br>
            {{($data['bid']['email'] ?? '')}}
        </td>
    </tr>
    <tr>
        <td class="w-50 br bb bl pl-10">
            2.1 Partener (intermediar de credit)
        </td>
        <td class="w-50 br bb pl-10">
            <span class="strong">
            {{$data['dealer']['full_name']}}
{{--            c.f. {{$data['dealer']['idno']}}<br>--}}
{{--            IBAN {{$data['dealer']['bank_iban']}}<br>--}}
{{--            {{$data['dealer']['bank_name']}}<br>--}}
{{--            BIC SWIFT: {{$data['dealer']['bank_swift']}}<br>--}}
{{--            TVA {{$data['dealer']['bank_tva']}}<br>--}}
{{--            {{$data['dealer']['address_jju']}}<br>--}}
{{--            Email: {{$data['dealer']['email']}}<br>--}}
{{--            {{$data['dealer']['website']}}<br>--}}
{{--            Director {{$data['dealer']['director_general']}}--}}
        </td>
    </tr>
</table>

<table border="1" cellpadding="2" cellspacing="0" style="border: 1px solid #000000;width: 100%;margin:15px 0;">
    <tr>
        <td class="strong text-center">Număr<br>rate</td>
        <td class="strong text-center">Data plății</td>
        <td class="strong text-right">Rambursări<br>suma de bază</td>
        <td class="strong text-right">Dobânda</td>
        <td class="strong text-right">Comision de<br>examinare</td>
        <td class="strong text-right">Comision de<br>administrare</td>
        <td class="strong text-right">Total de<br>plată</td>
    </tr>
    @foreach($data['bid']['bid_months'] as $k => $row)
    <tr>
        <td class="text-center">{{($k + 1)}}</td>
        <td class="text-center">{{Carbon::parse($row['date'])->format('d.m.Y')}}</td>
        <td class="text-right">{{$row['imprumut_per_luna']}}</td>
        <td class="text-right">{{$row['dobinda_per_luna']}}</td>
        <td class="text-right">{{$row['comision_per_luna']}}</td>
        <td class="text-right">{{$row['comision_admin_per_luna']}}</td>
        <td class="text-right">{{$row['total_per_luna']}}</td>
    </tr>
    @endforeach
    <tr>
        <td class="text-center"> </td>
        <td class="text-center">Total</td>
        <td class="text-right">{{round($row['bid']['imprumut'], 2)}}</td>
        <td class="text-right">{{round($row['bid']['total_dobinda'], 2)}}</td>
        <td class="text-right">{{round($row['bid']['total_comision'], 2)}}</td>
        <td class="text-right">{{round($row['bid']['total_comision_admin'], 2)}}</td>
        <td class="text-right">{{round($row['bid']['total'], 2)}}</td>
    </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" style="border: none;width: 100%;">
    <tr>
        <td class="pl-10">
            <span class="strong">
                Creditor: SCN „CREDIT BOX” SRL<br>
                mun. Bălți, str. Ștefan cel Mare, 57<br>
                c/f 1019602000472<br>
                BC VICTORIABANK SA, sucursala 1, Bălți, c.b.VICBMD2X740<br>
                IBAN MD65VI022510100000028MDL<br>
                Reprezentantul creditorului _____________
            </span>
        </td>
        <td class="pl-10">
            <span class="strong">
            Client: <?=($data['bid']['last_name'] ?? '')?> <?=($data['bid']['first_name'] ?? '')?> <?=($data['bid']['patronymic'] ?? '')?><br>
            a.n. {{Carbon::parse($data['bid']['birth_date'])->format('Y')}}<br>
            IDNP {{$data['bid']['idnp']}}<br>
            Seria {{$data['bid']['buletin_sn']}}<br>
            Act de identitate: Buletin de identitate<br>
            Eliberat de: {{$data['bid']['buletin_office']}}<br>
            Semnatura ___________________
            </span>
        </td>
    </tr>
</table>




@include('pdf.footer', $data)

