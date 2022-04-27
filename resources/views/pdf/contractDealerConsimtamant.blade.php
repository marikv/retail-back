<?php
use Carbon\Carbon;
?>
@include('pdf.header', $data)
<style>
    @page { margin: 0; }
    body { margin: 50px 40px 20px 80px; }
    body { line-height: 100%; }
</style>

<div id="footer"></div>


<div class="text-center title">
    <span class="strong">
        <span class="title2">
            CONSIMȚĂMÂNTUL CU PRIVIRE LA<br>
            PRELUCRAREA DATELOR CU CARACTER PERSONAL
        </span>
    </span>
</div>

<div>
    <div>
        <br>
        <br>
        <br>
        Numele, Prenumele / Denumirea firmei <span class="strong">{{$data['dealer']['full_name']}}</span>
        <br>
        <br>
        <br>
        <span class="strong">IDNP / IDNO {{$data['dealer']['idno']}}</span><br>
        Consimt și confirm următoarele:<br>
        - Prelucrarea datelor personale de către <strong>OCN „CREDIT BOX” SRL</strong> în scopul verificării veridicităţii informaţiei prezentate/ furnizate în scopul executării relaţiilor contractuale,
        <br>
        - accesarea, procesarea, prelucrarea, verificarea, actualizarea, publicarea, transmiterea transfrontaliera, colectarea datelor mele personale privind IDNP, numele, prenumele, patronimicul, sexul, cetățenia, data și locul nașterii, imaginea grafică (fotografia), situația familială, situația militară, datele de geolocalizare/datele din trafic, datele personale ale membrilor familiei, datele din permisul de conducere, situația economică și financiară, datele privind bunurile imobile și mobile deținute, locul de domiciliu/viza de reședință, datele bancare, semnătura, datele din actele de stare civilă, datele de contact (numarul de telefon fix, mobil, adresă electronica), numărul dosarului de pensie, codul personal de asigurări sociale, codul asigurării medicale, datele genetice, datele biometrice și antropometrice, profesia și locul de muncă, informațiile despre formarea profesională (diplome, studii), caracteristici fizice, solicitate de OCN „CREDIT BOX” SRL in scopul executarii prevederilor contractuale;
        <br>
        <br>
        <br>
        <br>
        Semnătura __________________ L.S
        <br>
        <br>
        <br>
        Data _________________
    </div>
</div>
@include('pdf.footer', $data)

