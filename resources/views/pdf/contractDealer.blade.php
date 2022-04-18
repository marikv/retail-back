<?php
use Carbon\Carbon;
?>
@include('pdf.header', $data)
<style>
    @page { margin: 20px; }
    body { margin: 20px; }
</style>

<div class="text-center title">
    CONTRACT DE COLABORARE Nr.<?=$data['dealer']->id?> din data de <?=$data['dealer']->contract_date?>
</div>
<? print_r($data['dealer']); ?>

<table border="0" style="border: none;width: 100%;">
    <tr>
        <td style="width: 270px;">Numele/prenumele beneficiarului/clientului</td>
        <td style="width: 230px;border-bottom: 1px solid #000000;"><?=($data['client']['last_name'] ?? '')?> <?=($data['client']['first_name'] ?? '')?> <?=($data['client']['patronymic'] ?? '')?></td>
        <td style="width:90px;text-align: right;">Domiciliul</td>
        <td style="border-bottom: 1px solid #000000;">Or balti str.....</td>
        <td style="width: 60px;text-align: right;">Data</td>
        <td style="width: 75px;border-bottom: 1px solid #000000;text-align: right;"><?=Carbon::parse($data['date_at'] ?? ($data['updated_at'] ?? 'now'))->format('d.m.Y')?></td>
    </tr>
</table>





<div class="text-center subtitle">1.asdfasdf</div>
<div style="width: 100%;"></div>




@include('pdf.footer', $data)

