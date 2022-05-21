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

<div id="footer">
    <table style="width: 100%; border: none;color: #626262" cellspacing="0" cellpadding="0">
        <tr>
            <td style="font-style:italic;text-align: center;vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 12px;">
                Creditor ______________________
                <div style="position: relative;" >
                    <div style="position: absolute;z-index: 999999;top: -110px; left: 50px;" class="stampila-semnatura"></div>
                </div>
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
            <span class="strong">{{($data['bid']['last_name'] ?? '')}} {{($data['bid']['first_name'] ?? '')}} {{($data['bid']['patronymic'] ?? '')}}</span><br>
            {{($data['bid']['address'] ?? '')}}<br>
            {{($data['bid']['phone1'] ?? '')}}<br>
            {{($data['bid']['email'] ?? '')}}
        </td>
    </tr>
    <tr>
        <td class="w-50 br bb bl pl-10">
            2.1 Beneficiar (intermediar de credit) în temeiul Contractului de Colaborate Nr.{{$data['dealer']['id']}} din {{ Carbon::parse($data['dealer']['contract_date'])->format('d.m.Y') }}
        </td>
        <td class="w-50 br bb pl-10">
            {{$data['dealer']['full_name']}}<br>
            c.f. {{$data['dealer']['idno']}}<br>
            IBAN {{$data['dealer']['bank_iban']}}<br>
            {{$data['dealer']['bank_name']}}<br>
            BIC SWIFT: {{$data['dealer']['bank_swift']}}<br>
            TVA {{$data['dealer']['bank_tva']}}<br>
            {{$data['dealer']['address_jju']}}<br>
            Email: {{$data['dealer']['email']}}<br>
            {{$data['dealer']['website']}}<br>
            Director {{$data['dealer']['director_general']}}
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-left title2 bl bb br pl-10">
            <span class="strong">3. Descrierea principalelor caracteristici ale produsului de creditare</span>
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.1 Tipul de credit
        </td>
        <td class="br bb pl-10">
            Credit legat
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.2 Destinația creditului
        </td>
        <td class="br bb pl-10">
            Creditul este oferit în scopul procurarii bunurilor și sau serviciilor  comercializate de beneficiar ({{$data['bid']['produs']}})
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.3 Suma totala a creditului  acordat
            transferata în contul intermediarului de credit
        </td>
        <td class="br bb pl-10">
            - suma in MDL {{$data['bid']['imprumut']}} lei
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.4 Termenul de rambursare
        </td>
        <td class="br bb pl-10">
            - {{$data['bid']['months']}} luni
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.5 Denumirea Creditului
        </td>
        <td class="br bb pl-10">
            {{$data['bid']['type_credit']['product']['name']}}
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.6 Costurile creditului
            Suma MDL (totala rambursabila) {{$data['bid']['total']}} lei
        </td>
        <td class="br bb pl-10">
            - rata dobânzii aferente creditului: {{round((double)$data['bid']['bid_months'][0]['dobinda_per_luna'] / (double)$data['bid']['imprumut'] * 100, 2)}} %, lunar calculat din suma initiala a creditului, indicat in p.c 3.3,<br>
            - comision de examinare a creditului {{round((double)$data['bid']['type_credit']['comision'], 2)}}% lunar din soldul initial,<br>
            - comision de administrare lunara {{round((double)$data['bid']['type_credit']['comision_admin'], 2)}}%; calculat incepînd cu data de {{Carbon::parse($data['bid']['bid_date'])->format('d.m.Y')}}.<br>
            - costul transferului la contul bancar sau eliberarii în numerar catre Partener - nu mai mult de 1,5% din suma transferata în contul intermediarului de credit.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.7 Formula de calcul al Dobînzii Anual Efective (DAE)<br>
            <strong>DAE - {{$data['bid']['dae']}}%</strong>
        </td>
        <td class="br bb pl-10">
            <div class="dae-formula" style="border: none;"></div>
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            3.8 Modalitățile de achitare a Creditului
        </td>
        <td class="br bb pl-10">
            <span class="strong">- în numerar, la oficiile Creditorului;<br>
            - prin transfer, în contul bancar al Creditorului:<br>
            BC VICTORIABANK SA, sucursala 1, Bălți, c.b.VICBMD2X740<br>
            IBAN MD65VI022510100000028MDL<br>
            - terminalele Run Pay<br>
                - platile online pe site-ul</span> https://oplata.md  <strong>si</strong>  https://paynet.md<br>
            <strong>Nota:</strong> Sumele achitate în plus de Client, prin intermediul sistemului Run Pay, vor fi considerate ca avans și pot fi utilizate de client la achitarea costurilor creditului pentru împrumuturile viitoare. La fel, clientul poate ridica în numerar suma achitată în plus, la oricare din oficiile Creditorului timp de 3 ani din momentul încasării avansului.
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-left title2 bl bb br pl-10">
            <span class="strong">4. Consecințe de neexecutare a plaților</span>
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            <strong> 4.1 Penalitatea contractuala</strong><br>
            Dacă Clientul nu achită în termenul stabilit sumele datorate conform graficului de rambursare a plaților  Creditorul are dreptul să solicite de la acesta să plătească o penalitate de întârziere, aplicată la suma neplătită a creditului, calculată pentru fiecare zi calendaristică de întârziere.

        </td>
        <td class="br bb pl-10">
            - Cite 1% din suma creditului restant, calculat pentru fiecare zi de întârziere;<br>
            Penalitatea de întârziere se calculează până la data stingerii complete a datoriei, aparte pentru fiecare rată lunară neachitata la scadenta, în conformitate cu graficul de rambursare a plaților.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            <span class="strong"> 4.2 Alte consecințe în caz de neexecutare</span> -în cazul în care  Clientul întârzie în realizarea rambursării plaților scadente, Creditorul va efectua apeluri telefonice și va expedia sms-uri la unul din numerele de contact indicate de Client în Contractul de credit, pentru a-l notifica asupra întârzierii plăţii și pericolului calculului penalității de întârziere
        </td>
        <td class="br bb pl-10">
            Dacă Clientul nu va achita creditul şi/sau alte plăţi scadente stabilite în Contract  în stricta conformitate cu graficul de rambursare a plaților Creditorul are dreptul să calculeze și sa pretinda  penalități pentru expedierea notificărilor privind întârzierea plăților, în mărime de – 200 lei pentru
            fiecare din primele trei notificări; suplimentar la penalitatea de întârziere indicată la p. 4.1 din Contract pentru expedierea notificării către Client cu privire la întârzierea plăţii, indicând valoarea sumelor restante, avertizarea privind pericolul de a pretinde plata imediată a ratelor neajunse la scadență conform Contractului de credit sau privind pericolul rezoluțiunii anticipate a Contractului.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            <span class="strong"> 4.3 Rezolutiune :</span>
                Rezoluțiunea Contractului nu se răsfrânge asupra obligaţiilor pecuniare scadente şi cele viitoare ce reies din suma datorată şi nu afectează dreptul Creditorului de a calcula în continuare penalitatea de întârziere.
                Clientul obţine statut de Debitor rau-  platnic în istoria sa creditară prezentă şi viitoare, şi împotriva acestuia va fi iniţiată procedura de urmărire silită pentru recuperarea plăţilor datorate conform Contractului de Credit precum şi pentru recuperarea prejudiciului cauzat şi a venitului ratat.
        </td>
        <td class="br bb pl-10">
            Dacă Debitorul întârzie în realizarea rambursării Contractului de credit, o perioadă mai mare de 90 de zile calendaristice, calculându-se de la ziua scadentă a realizării rambursării stipulate prin Contractul de credit, Creditorul declară scadente și plătibile imediat toate plăţile conform Contractului de credit și are dreptul de a rezolvi în mod unilateral Contractul de credit calculând suplimentar o penalitate în mărime de 20% din valoarea creditului neachitat, pentru rezoluțiunea anticipată a contractului.
            <br>
            - Creditorul va trimite o notificare, prin intermediul unei scrisori, despre rezoluțiunea anticipată, unilaterală, a Contractului de credit, şi obligaţia Clientului de a achita, în mod integral, în termen de 15 zile din momentul primirii notificării:
            <br>
            - suma creditului neplătit;
            <br>
            - dobânda neplătită și calculată până la data rezoluțiunii anticipate a contractului;
            <br>
            - suma restantă a comisionului de examinare a cererii, achitarea căruia a fost convenită de a fi efectuată eșalonat;
            <br>
            - alte comisioane dacă acestea au fost agreate de către părți;
            <br>
            - penalitatea de întârziere în condițiile p.4.1;
            <br>
            - costuri pecuniare contractuale în legătură cu expedierea notificărilor în condițiile p. 4.2 ;
            <br>
            - o penalitate contractuală în mărime de 20% din valoarea creditului neachitat pentru rezoluțiunea anticipată a Contractului de credit în condițiile p. 4.3 și un nou calcul al penalității calculată și neplătită pentru fiecare zi de întârziere aplicate la suma neplătită a creditului.
            <br>
            Contractul de credit se consideră reziliat din momentul primirii declarației de rezoluțiune de către cealaltă parte.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            <span class="strong"> 4.4 Ordinea de atribuire a plaților</span>
        </td>
        <td class="br bb pl-10">
            În cazul în care sumele plătite de către Client sunt insuficiente pentru acoperirea integrală a datoriei restante, atunci ordinea contabilă convenită pentru fiecare rambursare primită de Creditor este următoarea: din suma netă primită, mai întîi se calculează şi se deduc penalitățile (dacă este cazul), apoi alte taxe (dacă este cazul), apoi dobânda, apoi comisionul de examinare a cererii, achitarea căruia a fost convenită de a fi efectuată eșalonat, şi în cele din urmă suma creditului.
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-left title2 bl bb br pl-10">
            <span class="strong">5. Alte aspecte juridice importante</span>
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.1 Dreptul de revocare
        </td>
        <td class="br bb pl-10">
            Clientul dispune de dreptul să renunţe la Contractul de Credit Nebancar  în termen de 14 zile calendaristice din momentul încheierii prezentului contract prin transmiterea către Creditor a unei notificari scrise,  unilaterale de revocare a contractului.  În acest caz Clientul va restitui imediat  suma  Creditului oferit , achitînd  costurile creditului calculate la momentul rambursătii, conform prezentului Contract.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.2 Dreptul la rambursare anticipată
        </td>
        <td class="br bb pl-10">
            - Clientul dispune de dreptul la rambursare anticipată a creditului în orice moment.
            <br>
            - În  acest  caz,  Creditorul  are  dreptul  să  solicite  următoarele  sume:  suma  restantă  a  creditului (nerambursată), integral; suma restantă a comisionului de examinare a cererii de acordare a creditului, achitarea căruia a fost convenită de a fi efectuată eșalonat; alte comisioane și plăți dacă acestea sunt prevăzute de Graficul de Rambursare, cu excepția cazului în care Creditorul renunță unilateral la anumite comisioane în favoarea Clientului; Compensația prevăzută de art.20 din Legea nr.202 din 12.07.2013 după cum urmează:
            <br>
            a) 1% din valoarea creditului rambursat anticipat, dacă perioada de timp dintre rambursarea anticipată şi rezoluțiunea convenită a Contractului de Credit este mai mare de un an;
            <br>
            b) 0,5% din valoarea creditului rambursat anticipat, dacă perioada de timp dintre rambursarea anticipată şi data convenită pentru rezoluțiunea Contractului de Credit nu este mai mare de un an.
            <br>
            - În eventualitatea intenției de a rambursa anticipat creditul, Clientul este obligat, până la efectuarea oricărei plăți în temeiul prezentului Contract, să ia legătura cu Creditorul, folosind mijloacele de comunicare menționate în prezentul Contract, pentru înregistrarea solicitării de rambursare anticipată și obținerea calculelor exacte despre sumele ce urmează să fie achitate la ziua rambursării anticipate a creditului.
            <br>
            - Clientul  suportă toate costurile de rambursare şi se obligă să efectueze plățile în așa mod, încât Creditorul să primească suma de rambursare convenită netă şi fără careva deduceri de către orice terţe părţi care procesează plata de rambursare.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.3 Dreptul la un proiect al Contractului de credit
        </td>
        <td class="br bb pl-10">
            Clientul este în dreptul, la cerere, să obţină gratuit un exemplar al proiectului de contract de credit. Această dispoziţie nu se aplică în cazul în care, în momentul cererii, creditorul nu doreşte să încheie contractul de credit.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.4 Dreptul Clientului la  informații
        </td>
        <td class="br bb pl-10">
            Clientul este în drept să obțina gratuit informații privind drepturile și obliațiile sale ce reiese din prezentul Contract la oficiul creditorului, s-au numărul de telefon-023185858
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.5 Obliația Clientului de a-l informa pe Creditor
        </td>
        <td class="br bb pl-10">
            Clientul este obligat să-l notifice pe Creditor în termen   maxim de 3 zile  în caz de :
            <br>
            - schimbarea datelor de contact, adresa, locului de trai,
            locului de muncă, schimbarea numelui,
            <br>
            - modificarea situației financiare, precum și survenirea unor circumstanțe ce ar putea influența semnificativ executarea prezentului Contract.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.6 Obligația Creditorului la procesarea datelor cu caracter personal
        </td>
        <td class="br bb pl-10">
            Clientul va prezenta şi va transmite Creditorului datele sale cu caracter personal, iar Creditorul le va asigura protecția în conformitate cu legislația în vigoare şi obiectul prezentului Contract și le va prelucra în scopul executării acestui Contract.
            <br>
            Clientul acceptă şi împuternicește Creditorul, în mod necondiționat și cu libera voință, în scopul executării prezentului Contract, cu dreptul de a colecta, verifica, cerceta, acumula, utiliza, dezvălui, divulga şi de a transmite orice informaţie cu privire la Client, obţinută la încheierea și/sau în perioada executării    prezentului Contract fără a cere alte acorduri prealabile.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.7 Dreptul Creditorului de a comunica informația despre Client persoanelor terțe conform Contractului
        </td>
        <td class="br bb pl-10">
            În cazul neexecutării obligațiilor conform prezentului Contract (parțial sau integral), Clientul exprimă consimţământul şi confirmă dreptul Creditorului de a comunica informaţia despre Client şi istoria sa creditară, instituțiilor financiare (bănci comerciale, asociaţii de economii şi împrumut, organizaţii de creditare nebancară), Birourilor Istoriilor de Credit sau altor structuri similare, precum și companiilor de colectare a datoriilor, care vor acționa din numele Creditorului, oferindu-le dreptul de a colecta, înregistra, organiza, stoca, păstra,  modifica ori utiliza, dezvălui prin transmitere, a da publicității, alătura ori combina, şterge sau distruge datele cu caracter personal ale Clientului
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.8 Dreptul Creditorului de a cesiona drepturile (creanțele) conform Contractului către terți
        </td>
        <td class="br bb pl-10">
            Creditorul este în drept să cesioneze drepturile (creanțele) conform prezentului  Contract, persoanelor terțe cu informarea ulterioară a Clientului.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.9 Garanțiile Clientului
        </td>
        <td class="br bb pl-10">
            Semnînd prezentul Contract, Clientul garantează pe propria sa raspundere că:
            <br>
            - a prezentat Creditorului informații și acte veridice;
            <br>
            - numerele de telefon pe care le-a prezentat pot fi utilizate pentru a-l contacta, la fel ca mijloc de identificare;
            <br>
            - clauzele prezentului Contract au fost negociate între parți și corespund intereselor acestora;
            <br>
            - prezentul Contract a fost încheiat fara a fi influiențat de violență, amenințare, înșelăciune, nu se afla sub influiența carorva substanțe alcoolice, narcotice sau psihotrope,
            <br>
            - informația cu privire la condițiile de creditare i-au fost aduse la cunoștință din timp, Clientul a dispus de suficient timp pentru a le studia și accepta înnainte de încheierea prezentului Contract.
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.10 Metodele de soluționare a litigiilor
        </td>
        <td class="br bb pl-10">
            -negocieri;<br>
            -pe cale de pretenții în corespundere cu Regulamentul Creditorului;<br>
            -pe cale judiciară- în conformitate cu legislația în vioare;
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            5.11 Dispoziții finale
        </td>
        <td class="br bb pl-10">
            - prezentul Contract intră în vigoare din momentul transmiterii către Client a sumei Creditului și va fi valabil până la îndeplinirea tuturor angajamentelor asumate de către părţi în cadrul lui;
            <br>
            - prezentul contract a fost întocmit în 2 exemplare, din care un exemplar pentru fiecare dintre parți.
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-left title2 bl bb br pl-10">
            <span class="strong">6. Rechizitele și semnaturile părților</span>
        </td>
    </tr>
    <tr>
        <td class="br bb bl pl-10">
            <span class="strong">
                Creditor: OCN „CREDIT BOX” SRL<br>
                mun. Bălți, str. Ștefan cel Mare, 57<br>
                c/f 1019602000472<br>
                BC VICTORIABANK SA, sucursala 1, Bălți, c.b.VICBMD2X740<br>
                IBAN MD65VI022510100000028MDL<br>
                Reprezentantul creditorului _____________
            </span>
        </td>
        <td class="br bb pl-10">
            <span class="strong">
            Client: <?=($data['bid']['last_name'] ?? '')?> <?=($data['bid']['first_name'] ?? '')?> <?=($data['bid']['patronymic'] ?? '')?><br>
            a.n. {{Carbon::parse($data['bid']['birth_date'])->format('d.m.Y')}}<br>
            IDNP {{$data['bid']['buletin_idnp']}}<br>
            Seria {{$data['bid']['buletin_sn']}}<br>
            Act de identitate: Buletin de identitate<br>
            Eliberat de: {{$data['bid']['buletin_office']}}<br>
            Semnatura ___________________
            </span>
        </td>
    </tr>
</table>




@include('pdf.footer', $data)

