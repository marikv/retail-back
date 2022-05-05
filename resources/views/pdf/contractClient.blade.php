<?php
use Carbon\Carbon;
?>
@include('pdf.header', $data)
<style>
    @page { margin: 0; }
    body { margin: 60px 60px 120px 85px; }
</style>

<div id="footer">
    <table style="width: 100%; border: none;color: #626262" cellspacing="0" cellpadding="0">
        <tr>
            <td style="font-style:italic;text-align: center;vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 12px;">
                Creditor ______________________
            </td>
            <td style="font-style:italic;text-align: center;vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 12px;">
                Client ______________________
            </td>
        </tr>
    </table>
</div>


<div class="text-center title">
    <span class="strong">CONTRACT DE CREDIT NEBANCAR № {{$data['bid']['id']}}
    </span>
</div>

<table border="0" cellpadding="0" cellspacing="0" style="border: none;width: 100%;">
    <tr>
        <td style="width: 30%;">
            <div class="text-left title2">
                <span class="strong">mun. Bălți</span>
            </div>
        </td>
        <td style=""> &nbsp; </td>
        <td class="text-center" style="width: 170px;border-bottom: 1px solid #000000;height: 9px;">{{ Carbon::parse($data['bid']['bid_date'])->format('d.m.Y') }}</td>
    </tr>
    <tr style="height: 9px;">
        <td style="height: 9px;"> &nbsp; </td>
        <td style="height: 9px;"> &nbsp; </td>
        <td class="text-center" style="vertical-align: top; font-size: 11px;height: 9px;">(data încheierii contractului)</td>
    </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" style="border: none;width: 100%;">
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
            023185858<br>
            creditboxmd@gmail.com<br>
            &nbsp;
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
            Adresă<br>
            Nr. telefon*<br>
            Adresa de e-mail*
        </td>
        <td class="w-50 br bb pl-10">
            <span class="strong"><?=($data['bid']['last_name'] ?? '')?> <?=($data['bid']['first_name'] ?? '')?> <?=($data['bid']['patronymic'] ?? '')?></span><br>
            <?=($data['bid']['address'] ?? '')?><br>
            023185858<br>
            creditboxmd@gmail.com<br>
            &nbsp;
        </td>
    </tr>
</table>




@include('pdf.footer', $data)

