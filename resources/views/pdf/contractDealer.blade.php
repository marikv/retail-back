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
                <div style="position: relative;" >
                    <div style="position: absolute;z-index: 999999;top: -110px; left: 50px;" class="stampila-semnatura"></div>
                </div>
            </td>
            <td style="font-style:italic;text-align: center;vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 12px;">
                Beneficiar ______________________
            </td>
        </tr>
    </table>
</div>


<div class="text-center title">
    <span class="strong">CONTRACT DE COLABORARE Nr. {{$data['dealer']->id}}
        <span class="title2">
            din data de  {{ Carbon::parse($data['dealer']->contract_date)->format('d.m.Y') }}
        </span>
    </span>
</div>

<div class="text-left title2 mb-15">
    <span class="strong">mun. Bălți</span>
</div>

<div>
    <div>
        <span class="strong">OCN „CREDIT BOX” SRL</span>, c/f 1019602000472, cu sediul în mun. Balți, str. Ștefan cel Mare, 57 în persoana directorului
        <span class="strong">GRUMEZA Sergiu</span>,
        care acționează în temeiul Statutului, denumit în continuare
        <span class="strong">„CREDITOR”</span>, pe de o parte, și<br>
        <span class="strong">{{$data['dealer']->full_name}}</span>, c/f {{$data['dealer']->idno}}, cu sediul {{$data['dealer']->address_jju}},
        reprezentată de {{$data['dealer']->director_general}} în calitate de administrator care acționează în baza statutului,
        denumit în continuare  <span class="strong">„BENEFICIAR”</span>, pe de altă parte, și-au exprimat intenția să semneze acest contract conform clauzelor după cum urmează:
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">I. CONCEPTE GENERALE</span></span>
    </div>


    <div>
        1.1 „ CREDITOR” - OCN „CREDIT BOX” SRL, c/f 1019602000472, adresa juridica: mun. Balți, str. Ștefan cel Mare, 57  care, în conformitate cu legislația în vigoare, acordă cu titlu professional și desfașoara activitați de creditare nebancara.
        <br>1.2 „BENEFICIAR” - persoană juridică care își desfășoară activitățile în conformitate cu legislația aplicabilă și vinde sau prestează servicii pentru vânzarea de bunuri;
        <br>1.3 „BUNURI ALE BENEFICIARULUI” sau „BUNURI” - bunuri vândute de Beneficiar prin magazine, reprezentanțe sau puncte de vânzare (bunuri de uz casnic, echipamente digitale, alte bunuri).
        <br>1.4 CONTRACT DE CREDIT LEGAT– contract de credit care, din punct de vedere obiectiv, constituie o unitate comercială/economic şi serveşte exclusive finanţării unui contract ce are ca obiect furnizarea unor bunuri sau prestarea unui serviciu;
        <br>1.4 „CLIENT” - persoană care beneficiază de serviciile organizației de creditare nebancară ori persoană cu care organizația de creditare nebancară a negociat prestarea serviciilor de creditare nebancară, care și-a exprimat intenția de a achiziționa bunuri de la Beneficiar printr-un împrumut primit de la CREDITOR în acest scop;
        <br>1.5 CERERE DE ÎMPRUMUT- cerere scrisă depusă de Client  în scopul obținerii unui împrumut de consum, inclusiv pentru achiziționarea de bunuri sau servicii în credit, cererea fiind depusă într-un format aprobat sau acceptat de CREDITOR.
        <br>1.6 CONTRACT DE CREDIT NEBANCAR- contract de credit încheiat între CREDITOR și Client , cu toate anexele, și/sau acordurile adiționale ale acestuia, conform cărora CREDITORUL este obligat să transfere BENEFICIARULUI suma de bani necesară CLIENTULUI pentru achiziționarea bunurilor de la  BENEFICIAR, iar Clientul se obligă să restituie suma primită și costurile creditului în stricta conformitate cu  Contractul de Credit Nebancar.
        <br>1.7 COMISION DE EXAMINARE A CERERII - taxa fixă pentru creditul acumulat pentru procesarea și acceptarea unei cereri– se referă la suma plătită sau plătibilă de către Client catre CREDITOR pentru servicii financiare legate de examinarea și procesarea cererii depuse de catre Client. Taxa de procesare a cererii se achită în cazul aprobării cererii de împrumut, la momentul încheierii/semnării Contractului de credit, Creditorul este în drept să rețină, taxa de procesare a cererii pentru creditul care urmează să fie transferat și să permită plata pentru procesarea cererii în rate, repartizate pe scadența împrumutului principal, în conformitate cu termenii și graficul de rambursare a contractului de credit.
        <br>1.8 ACORDAREA CREDITULUI – se realizează la solicitarea Clientului prin virare în contul BENEFICIARULUI Creditul este considerat debursat în momentul în care suma împrumutului este transferată în contul bancar al BENEFICIARULUI, conform prevederilor din Contractul de credit nebancar.
        <br>1.9 COSTUL TOTAL AL CREDITULUI - toatecosturile, inclusive dobînda, comisioanele, taxele şi orice alt tip de costuri pe care trebuie să le suporte CLIENTUL în legătură cu contractul de credit şi care sînt cunoscute de CREDITOR.
        <br>1.10 DOBÎNDA ANUALA EFECTIVA (DAE) – costul total al creditului pentru consummator exprimat ca procent anual din valoarea totală a creditului.
        <br>1.11 VALOAREA TOTALA A CREDITULUI – sumele total epuse la dispoziţie în baza unui contract de credit;
        <br>1.12 VALOAREA TOTALA PLATIBILA DE CLIENT – suma dintre valoarea totală a creditului şi costul total al creditului pentru consumator.
        <br>1.13 GRAFIC DE PLAȚI CONFORM CONTRACTULUI DE CREDIT –informaţia cu privire la sumele şi datele (perioadele) de plată ce revin consumatorului conform contractului de credit pentru consumatori, care se coordonează între creditor şi consummator şi este parte integrantă a contractului de credit pentru consumatori;
        <br>1.14 CALCULUL TERMENILOR - la calcularea termenilor, în conformitate cu Contractul de credit nebancar, cuvântul „zi” înseamnă „zi calendaristică”, dacă nu se prevede altfel.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">II. OBIECTUL CONTRACTULUI</span></span>
    </div>
    <div>
        2.1 CREDITORUL  furnizează servicii de creditare  Clienților BENEFICIAULUI, iar BENEFICIARUL se obligă să presteze servicii de vânzare de bunuri Clienților săi.
        <br>2.2 Părțile se angajează să respecte cu bună-credință prevederile prezentului Contract.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">III. PERIOADA CONTRACTULUI</span></span>
    </div>
    <div>
        3.1 Prezentul contract intră în vigoare la momentul semnării lui de către părți.
        <br>3.2 Termenul Contractului acord este de 24  (douăzeci și patru) luni de la data semnării. În cazul în care părțile nu își exprimă intenția în scris de a rezilia prezentul contract, acordul se consideră prelungit automat de către părți.
    </div>


    <div class="title-contract text-center">
        <span class="title2"><span class="strong">IV. DREPTURILE ȘI OBLIGAȚIILE CREDITORULUI</span></span>
    </div>
    <div>
        4.1 Creditorul se obligă să ia în considerare posibilitatea de a acorda un credit Clientului pentru a acoperi integral sau parțial prețul bunurilor achiziționate de Client de la BENEFICIAR
        <br>4.2  În vederea îndeplinirii prezentului acord, CREDITORUL va oferi împrumuturi Clienților BENEFICIARULUI, în conformitate cu termenii și condițiile convenite în  acorduri adiționale, care fac parte integrantăa Contractului..
        <br>4.3 CREDITORUL se obligă să proceseze cererile de împrumut depuse de Client în termen de 15 (cincisprezece) minute de la momentul primirii acestora prin intermediul site-ului https://creditbox.md/.
        <br>4.4  Părțile au convenit că CREDITORUL are dreptul de a prelungi termenul limită de procesare a cererii de împrumut menționat la p. 4.3 până la 24 de ore, în cazul în care clientul a furnizat în mod intenționat sau nu informații false, inexacte sau incomplete în stadiul solicitării unui împrumut, și în cazurile în care denaturarea și/sau întârzierea datelor a fost din vina reprezentantului Beneficiarului care a asistat clientului la completarea cererii de credit.
        <br>4.5 Ca urmare a examinării și procesării cererii de împrumut, precum și în cazul respectării cerințelor propuse de CREDITOR în raport cu Clientul,  CREDITORUL decide să acorde un împrumut sau, în funcție de circumstanțe, să refuze acordarea acestuia.
        <br>4.6 CREDITORUL nu este obligat să își justifice decizia, fie că este pozitivă sau negativă, și nu va fi răspunzător față de Client, BENEFICIAR  sau un  alt terț.
        <br>4.7 CREDITORUL  este obligat să informeze BENEFICIARUL  și Clientul despre decizia luată pe marginea cererii de credit.
        <br>4.8 După semnarea Contractului de Credit Nebancar cu Clientul, CREDITORUL transferă suma împrumutului în contul BENEFICIARULUI  într-un termen care nu depășește (două) zile lucrătoare de la data semnării Contractului de Credit Nebancar  cu Clientul .
        <br>4.9 În cazul în care, după semnarea Contractului de Credit Nebancar cu CREDITORUL, clientul nu ridică bunurile solicitate de la BENEFICIAR în termen de 14 zile de la data semnării contractului, CREDITORULare dreptul de a cere restituirea sumei de bani transferate în conformitate cu Contractul de credit semnat cu Clientul.
        <br>4.10 În cazul rezilierii contractului dintre CREDITOR și BENEFICIAR, CREDITORUL își rezervă dreptul de a utiliza toate măsurile prevăzute de lege, precum și Contractul de Credit Nebancar , în vederea recuperării pierderilor de la partea vinovată. CREDITORUL va anunța clientul despre acest lucru.
        <br>4.11 CREDITORUL nu este responsabil pentru calitatea bunurilor si serviciilor oferite de BENEFICIAR.
        <br>4.12 CREDITORULare dreptul de a modifica unilateral condițiile de acordare a împrumuturilor menționate în clauza 4.2, sub rezerva notificării cu cel puțin 15 (cincisprezece) zile lucrătoare înainte de intrarea în vigoare a acestora.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">V. DREPTURILE ŞI OBLIGAŢIILE BENEFICIARULUI</span></span>
    </div>
    <div>
        5.1. BENEFICIARUL se obligă să ofere și să promoveze serviciile de creditare oferite deCREDITOR, în termenii și condițiile convenite în cadrul prezentului Contract și în conformitate cu cerințele interne aleCREDITORULUI, în vederea achiziționării de bunuri de la BENEFICIAR de către Client.
        <br>5.2. BENEFICIARULeste obligat să informeze clientul cu privire la termenii contractului de credit oferit de CREDITOR, care poate fi solicitat de client sau oferit acestuia în vederea achiziționării de bunuri.
        <br>5.3. BENEFICIARUL se obligă să asiste clientul în completarea cererii de împrumut prin intermediul site-ului https://retail.creditbox.md/ și transmiterea cererii către CREDITOR folosind modulul de trimitere al site-ului https://retail.creditbox.md/, acesta înţelegându-se că clientul furnizează acte de certificare.
        <br>5.4. Ca urmare a acceptării pozitive a creditului de către CREDITOR, acesta invită clientul la biroul BENEFICIARULUI pentru a semna Contractul de Credit cu toate anexele corespunzatoare.  Contractul de Credit trebuie să fie însoțit de copiile actelor de identitate a Clientului și Declarația de Consimțământ a Clientului privind verificarea dateIlor cu caracter personal.
        <br>5.5. Imediat după semnarea Contractului de Credit cu clientul, BENEFICIARUL poate transfera bunurile solicitate în proprietatea clientului, în acest sens fiind semnat si actul de primire-predare a bunului.
        <br>5.6. Imediat după semnarea Contractului de Credit  cu clientul, BENEFICIARULUl trimite CREDITORULUI prin intermediul site-ului https://retail.creditbox.md/  copii electronice (scanate) ale documentelor specificate în clauza 5.4.
        <br>5.7. În cazul în care CREDITORUL descoperă că BENEFICIARULUa făcut una sau mai multe abateri de la procedura de întocmire a contractelor decredit, ceea ce face imposibilă prelucrarea datelor cuprinse în contract pentru transferul ulterior al sumei împrumutului în contul BENEFICIARULUI, CREDITORUL  va anunta imediat BENEFICIARUL despre situatie prin trimiterea unui mesaj la adresa de e-mail la adresa creditboxmd@gmail.com, indicând numele și prenumele clientului, numărul contractului de credit nebancar  și o descriere a abaterii, astfel încât BENEFICIARUL să elimine, pe cont propriu și pe cheltuiala proprie, consecințele abaterii, în limita 3 (trei) zile lucrătoare de la data primirii alertelor relevante.
        <br>5.8. BENEFICIARUL este singurul responsabil pentru eliminarea consecintelor abaterilor de la procedura de incheiere a contractelor de imprumut intervenite din vina sa.
        <br>5.9. BENEFICIARUL are obligația imperativa de a transmite catre  CREDITOR a  Contractele de credit nebancar cu toate anexele și acordurile adiționale contractate cu Clientii, cel putin o data la 14 zile.
        <br>5.10. CREDITORUL are dreptul de a cere, iar BENEFICIARUL  are dreptul de a accepta plata în numele și în favoarea Clientului, plata corespunzătoare pentru procesarea cererii sau a unei părți a acesteia, în conformitate cu condițiile de acordare a creditelor prevăzute în Anexa la Contractul de cooperare. Plata de către Beneficiar a plății pentru procesarea cererii sau a unei părți a acesteia se va face în contul CREDITORULUI într-un termen care nu depășește 2 (două) zile lucrătoare din momentul în care CREDITORUL  transferă suma împrumutului în contul BENEFICIARULUI.
        <br>5.11. În cazul în care, după semnarea Contractului de Credit  cu CREDITORUL, clientul nu ridică bunurile solicitate de la BENEFICIAR  în termen de 14 zile de la data semnării contractului,  BENEFICIARUL este obligat să informeze CREDITORUL despre aceasta și să remită în contul CREDITORULUI în termen 2 zile bancare suma de bani transferata în conformitate cu Contractul de credit  semnat cu clientul.
        <br>5.12. În cazul în care, după semnarea Contractului de  credit  , Clientul a returnat bunurile în cazurile prevăzute la art. 17-19 din Legea Protectiei Drepturilor Consumatorului, BENEFICIARUL este obligat sa informeze CREDITORUL despre aceasta, si sa restituie in contul CREDITORULUI in termen de 2 zile bancare de la data returnarii bunurilor, suma de bani virata de CREDITOR  catre Contul BENEFICIARULUI conform contractului de credit semnat cu clientul.
        <br>5.13. În cazul în care BENEFICIARUL nu respectă termenul de rambursare, BENEFICIARUL va trebui să plătească o penalitate de 0,1% pentru fiecare zi de întârziere până la transferul integral al creanței și penalitatea calculată. In cazul inceperii calculului penalitatii se plateste mai intai penalitatea, iar apoi imprumutul.
        <br>5.14. BENEFICIARUL este obligat să se asigure că mărfurile vândute sunt în siguranță și respectă cerințele prescrise sau declarate și este, de asemenea, obligat să asigure condițiile tehnice ale mărfurilor stabilite de producător.
        <br>5.15. BENEFICIARUL este singurul responsabil pentru daunele produse de produsul neconform pe toată perioada specificată de funcționare sau valabilitate, sub rezerva respectării de către consumator a regulilor de transport, depozitare, utilizare și consum.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">VI. CERINȚE DE CONFIDENTIALITATE</span></span>
    </div>
    <div>
        6.1 Părțile convin să mențină și să asigure confidențialitatea informațiilor de la terți cu care intră în contact, a proceselor de lucru sau a rezultatelor economice care au devenit sau pot deveni cunoscute părților în legătură cu executarea prezentului Contract.
        <br>6.2 Obligația prevăzută la clauza 6.1 rămâne valabila și imperativa după încetarea prezentului contract pentru cel puțin 24  luni.
        <br>6.3 Pentru încălcarea prevederii de confidențialitate, CREDITORUL va putea pretinde de la  BENEFICIAR  dreptul la despagubiri  calculate în raport cu prejudiciul material și/sau moral cauzat.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">VII. TERMENI DE MODIFICARE, ÎNCETARE</span></span>
    </div>
    <div>
        7.1 Modificările și/sau completările la acest Contract sunt efectuate numai prin Acorduri Adiționale.
        <br>7.2 Următoarele prevederi conduc la rezolutiunea contractului, cu asumarea consecințelor ce decurg din nerespectarea prevederilor:
        <br>a) nerespectarea totală sau parțială, inclusiv nerespectarea repetată a termenilor și/sau condițiilor specificate în contract;
        <br>b) expirarea termenului Contractului  fără a exprima, voința generală a părților de a prelungi perioada de valabilitate;
        <br>c) lichidarea uneia dintre părți (indiferent de motive: faliment, separare, fuziune etc.). Părțile se obligă să raporteze lichidarea în termen de 10 zile.
        <br>7.3 Neîndeplinirea parțială sau totală a termenilor și/sau condițiilor specificate în contract, executarea necorespunzătoare a cel puțin unei prevederi din contract poate atrage răspunderea părții răspunzătoare de pagubă.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">VIII. NOTIFICARI</span></span>
    </div>
    <div>
        8.1 Orice notificare va fi făcută în scris și livrată părții la adresa specificată în contract sau la noua adresă dacă s-a schimbat și a fost comunicată în scris.
        <br>8.2 Notificările pot fi trimise prin e-mail de confirmare, fax de confirmare, poștă recomandată sau curier, cu efect numai după confirmarea de către destinatar.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">IX. SOLUȚIONAREA LITIGIILOR</span></span>
    </div>
    <div>
        9.1 Părțile vor soluționa pe cale amiabilă eventualele dispute, neînțelegeri și conflicte care au apărut între ele în legătură cu implementarea prezentului acord. In situatia in care nu se poate ajunge la o rezolvare pe cale amiabila, orice litigiu decurge din/sau in legatura cu prezentul contract, inclusiv cele legate de incheierea, executarea, modificarea sau incetarea acestuia, se va solutiona in instantele de judecata  competente a Republicii Moldova. .
        <br>9.2 Aspectele care nu au fost reglementate prin prezentul acord vor respecta prevederile legii aplicabile.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">X. FORȚĂ MAJORĂ</span></span>
    </div>
    <div style="margin-bottom: 30px;">
        10.1 Circumstanțele de forță majoră, așa cum sunt definite de sistemul juridic al Republicii Moldova, exonerează de răspundere partea care se referă la acestea în condițiile legii. Partea care invocă forța majoră va notifica celeilalte părți situația în termen de 15 zile de la apariția forței majore.
    </div>

    <div class="title-contract text-center">
        <span class="title2"><span class="strong">XI. DISPOZIȚII FINALE ȘI TRANZITORII</span></span>
    </div>
    <div>
        11.1. Prezentul contract poate fi reziliat unilateral, cu condiția ca partea care inițiază să notifice cealaltă parte în scris cu 30 de zile calendaristice înainte de rezilierea planificată.
        <br>11.2. Prezentul Contract se încheie în două exemplare având aceeași forță juridică, în limba romînă, câte un exemplar pentru fiecare dintre părți.
    </div>

    <table style="width: 100%; border: none;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 13px;">
                    <div class="title-contract">
                        <span class="title2"><span class="strong">CREDITOR</span></span>
                    </div>

                    OCN „CREDIT BOX” SRL<br>
                    c/f 1019602000472<br>
                    IBAN MD65VI022510100000028MDL<br>
                    BC VICTORIABANK SA sucursala 1, Bălți,<br>
                    BIC SWIFT VICBMD2X740<br>
                    Adresa mun. Bălți, str. Ștefan cel Mare, 57<br>
                    Email creditboxmd@gmail.com<br>
                    https://creditbox.md/<br>
                    <br>
                    Director<br>
                    Grumeza Sergiu ______________________

                    <div style="position: relative;" >
                        <div style="position: absolute;z-index: 999999;top: -110px; left: 50px;" class="stampila-semnatura"></div>
                    </div>
                </td>
                <td style="vertical-align: top; width: 50%;padding-bottom: 7px;padding-top: 12px;font-size: 13px;">
                    <div class="title-contract">
                        <span class="title2"><span class="strong">BENEFICIAR</span></span>
                    </div>
                    {{$data['dealer']->full_name}}<br>
                    c.f. {{$data['dealer']->idno}}<br>
                    IBAN {{$data['dealer']->bank_iban}}<br>
                    {{$data['dealer']->bank_name}}<br>
                    BIC SWIFT: {{$data['dealer']->bank_swift}}<br>
                    TVA {{$data['dealer']->bank_tva}}<br>
                    {{$data['dealer']->address_jju}}<br>
                    Email: {{$data['dealer']->email}}<br>
                    {{$data['dealer']->website}}<br>
                    Director<br>
                    {{$data['dealer']->director_general}} _________________________
                </td>
            </tr>
    </table>
</div>
@include('pdf.contractDealerFooter', $data)

