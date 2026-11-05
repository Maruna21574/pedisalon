<?php
/** Obsah podstránok jednotlivých služieb. */
return [

'pedikura.php' => [
    'active' => 'pedikura',
    'title'  => 'Pedikúra',
    'desc'   => 'Kompletná pedikúra aj pedikúra s gél lakom. Šetrná starostlivosť o chodidlá, nechty a kutikuly v PediSalóne ZK.',
    'h1'     => 'Pedikúra',
    'lead'   => 'Kompletná starostlivosť o chodidlá, nechty a kožu – pre krásny vzhľad a pocit ľahkosti pri každom kroku.',
    'services' => ['pedikura', 'pedikura-gel'],
    'intro'  => [
        'Pravidelná pedikúra nie je len o estetike. Správne upravené nechty, uvoľnená koža a ošetrené päty predchádzajú nepríjemným problémom a nohy sa vám poďakujú pohodlím.',
        'Pri každej návšteve si najprv prezrieme stav vašich chodidiel a podľa toho zvolíme postup. Na záver môžete nechty nechať prirodzené alebo si vybrať farbu – klasický lak či trvanlivý gél lak.',
    ],
    'includes' => ['Relaxačný kúpeľ chodidiel', 'Úprava a tvarovanie nechtov', 'Ošetrenie kutikúl', 'Odstránenie zrohovatenej kože', 'Hydratačný krém a masáž chodidiel', 'Voliteľne lakovanie alebo gél lak'],
    'steps' => [['Konzultácia', 'Prezrieme nohy a dohodneme očakávania.'], ['Úprava', 'Nechty, kutikuly a zrohovatená koža.'], ['Starostlivosť', 'Hydratácia a relaxačná masáž.'], ['Finálny vzhľad', 'Lak alebo gél lak podľa výberu.']],
    'tips' => ['Návštevu opakujte približne každé 4–6 týždňov.', 'Chodidlá denne hydratujte krémom s urea.', 'Noste pohodlnú obuv, ktorá netlačí na prsty.'],
    'photos' => ['pedikura-1', 'pedikura-3', 'pedikura-5', 'pedikura-7', 'pedikura-9', 'pedikura-10'],
    'faq' => [
        ['Aký je rozdiel medzi pedikúrou a pedikúrou s gél lakom?', 'Pri pedikúre s gél lakom po kompletnom ošetrení pridáme gél lak, ktorý vydrží niekoľko týždňov bez odlupovania.'],
        ['Bolí pedikúra?', 'Nie. Pracujeme šetrne a ak máte citlivé miesta, stačí nám to povedať.'],
        ['Ako dlho vydrží gél lak?', 'Zvyčajne 3–4 týždne, podľa rýchlosti rastu nechtov a starostlivosti.'],
    ],
],

'brusenie-paty.php' => [
    'active' => 'brusenie',
    'title'  => 'Brúsenie päty a mozoľov',
    'desc'   => 'Šetrné odstránenie zrohovatenej kože, mozoľov a ošetrenie popraskaných pätiek. Hladké a mäkké päty v PediSalóne ZK.',
    'h1'     => 'Brúsenie päty a mozoľov',
    'lead'   => 'Hladké, mäkké a zdravo vyzerajúce päty bez zrohovatenej kože a popraskania.',
    'services' => ['brusenie'],
    'intro'  => [
        'Suché, zhrubnuté a popraskané päty sú častým problémom – nepekne vyzerajú, no hlavne môžu byť bolestivé. Šetrným brúsením odstránime nadbytočnú zrohovatenú kožu a mozole.',
        'Po ošetrení dostanete odporúčanie, ako päty udržať mäkké aj doma, aby sa problém čo najneskôr vrátil.',
    ],
    'includes' => ['Zmäkčenie kože chodidiel', 'Šetrné odstránenie zrohovatenej kože', 'Ošetrenie mozoľov', 'Vyhladenie povrchu päty', 'Výživný krém na päty', 'Rady pre domácu starostlivosť'],
    'steps' => [['Posúdenie', 'Zistíme rozsah zrohovatenia a popraskania.'], ['Brúsenie', 'Postupne a šetrne odstránime nadbytočnú kožu.'], ['Vyhladenie', 'Jemná úprava povrchu päty.'], ['Hydratácia', 'Výživný krém a tipy na doma.']],
    'tips' => ['Päty po sprche každý deň natrite hydratačným krémom.', 'Vyhnite sa otvorenej obuvi, ktorá odkrýva päty celé dni.', 'Doma nepoužívajte žiletky ani ostré nástroje.'],
    'photos' => ['brusenie-1', 'brusenie-2', 'brusenie-3', 'brusenie-4'],
    'faq' => [
        ['Ako často mám prísť na brúsenie päty?', 'Orientačne raz za 4–6 týždňov, pri silnom zrohovatení aj častejšie.'],
        ['Môžem brúsenie kombinovať s pedikúrou?', 'Určite. Pri kompletnej pedikúre sa zrohovatená koža ošetruje tiež; samostatná služba je ideálna, ak potrebujete riešiť najmä päty.'],
        ['Čo ak mám cukrovku?', 'Prosím, upozornite nás pri rezervácii. Pri diabetickej nohe voláme starostlivosť na mieru a odporúčame konzultáciu s lekárom.'],
    ],
],

'mykoza.php' => [
    'active' => 'mykoza',
    'title'  => 'Ošetrenie nechtov postihnutých mykózou',
    'desc'   => 'Šetrná úprava nechtov postihnutých mykózou a poradenstvo v PediSalóne ZK. Diskrétne a bez zbytočného stresu.',
    'h1'     => 'Mykóza nechtov',
    'lead'   => 'Diskrétna a šetrná úprava zhrubnutých či zmenených nechtov, aby ste sa zase cítili príjemne.',
    'services' => ['mykoza'],
    'intro'  => [
        'Zmenená farba, zhrubnutie alebo drobivosť nechtov môžu byť príznakom mykózy. Takéto nechty zvyčajne vyzerajú nepekne a môžu tlačiť v obuvi.',
        'V našom salóne vám nechty šetrne upravíme, zbavíme nadbytočnej hrúbky a poradíme s následnou starostlivosťou. Nejde o lekárske ošetrenie – liečbu mykózy určuje a vedie lekár.',
    ],
    'includes' => ['Šetrné skrátenie a zbrúsenie zhrubnutých nechtov', 'Odstránenie nadbytočných nánosov', 'Dôkladná hygiena a dezinfekcia pomôcok', 'Ošetrenie okolitej kože', 'Odporúčania na každodennú starostlivosť', 'Diskrétny a nevtieravý prístup'],
    'steps' => [['Prezretie', 'Pozrieme sa na stav nechtov a okolitej kože.'], ['Úprava', 'Šetrne skrátime a zbrúsime postihnuté nechty.'], ['Ošetrenie', 'Starostlivosť o kožu a nechtové lôžko.'], ['Poradenstvo', 'Tipy na prevenciu opakovania.']],
    'tips' => ['Obuv pravidelne vetrajte a dezinfikujte.', 'Používajte vlastné uterák a ponožky z prírodných materiálov.', 'V spoločných sprchách a bazénoch noste vlastnú obuv.', 'Pri podozrení na mykózu navštívte lekára, ktorý určí liečbu.'],
    'note'  => 'Dôležité: Naše služby nenahrádzajú lekárske vyšetrenie ani liečbu. Ak máte podozrenie na plesňové ochorenie, odporúčame návštevu kožného lekára; my vám nechty upravíme tak, aby ste sa cítili komfortne.',
    'photos' => ['mykoza-1', 'mykoza-2'],
    'faq' => [
        ['Vyliečite mykózu nechtov?', 'Nie, liečbu určuje lekár. My ponúkame šetrnú úpravu nechtov a poradenstvo v starostlivosti.'],
        ['Je ošetrenie bezpečné pre ostatných klientov?', 'Áno. Používame prísne hygienické postupy a pomôcky po každom klientovi čistíme a dezinfikujeme.'],
        ['Mám sa pred návštevou nejako pripraviť?', 'Nie, stačí prísť. Ak už používate lieky alebo laky od lekára, povedzte nám o tom.'],
    ],
],

'bradavice-kurie-oka.php' => [
    'active' => 'bradavice',
    'title'  => 'Ošetrenie bradavíc a kurích očí',
    'desc'   => 'Precízne ošetrenie kurích očí a bradavíc na chodidlách v PediSalóne ZK. Šetrne a s dôrazom na komfort.',
    'h1'     => 'Bradavice a kurie oká',
    'lead'   => 'Precízne a šetrné ošetrenie problémových miest na chodidlách, ktoré vám berú pohodlie pri chôdzi.',
    'services' => ['bradavice'],
    'intro'  => [
        'Kurie oká a bradavice na chodidlách dokážu poriadne znepríjemniť každý krok. Vznikajú z tlaku, trenia alebo vírusovej infekcie, a ich vzhľad aj príčina sa líšia.',
        'Pri návšteve najprv posúdime, o aký problém ide. Kurie oká a zrohovatené miesta ošetríme šetrne priamo u nás. Ak by išlo o nejasný nález, odporučíme vám návštevu lekára.',
    ],
    'includes' => ['Posúdenie problémového miesta', 'Šetrné odstránenie kurieho oka', 'Ošetrenie okolitej zrohovatenej kože', 'Ochrana ošetreného miesta', 'Odporúčania, ako problému predchádzať'],
    'steps' => [['Posúdenie', 'Zistíme, o aký typ problému ide.'], ['Ošetrenie', 'Precízne a šetrne odstránime postihnuté miesto.'], ['Ochrana', 'Ošetrené miesto vhodne ochránime.'], ['Prevencia', 'Poradíme, ako predísť návratu.']],
    'tips' => ['Zvoľte obuv správnej veľkosti s dostatkom miesta pre prsty.', 'Kurie oká nikdy nerežte doma – hrozí poranenie a infekcia.', 'Bradavice sa prenášajú vlhkým prostredím, nechoďte naboso po spoločných priestoroch.'],
    'note'  => 'Pri bolestivých, krvácajúcich, rýchlo rastúcich alebo nejasných útvaroch odporúčame najskôr vyšetrenie u lekára.',
    'photos' => ['bradavice-1', 'bradavice-2', 'bradavice-3', 'bradavice-4'],
    'faq' => [
        ['Je ošetrenie bolestivé?', 'Pracujeme veľmi šetrne a postupne. Väčšina klientov vníma skôr úľavu.'],
        ['Môže sa kurie oko vrátiť?', 'Ak pretrváva tlak alebo trenie (nevhodná obuv, deformita prstov), môže sa vrátiť. Preto radíme aj s prevenciou.'],
        ['Odstránite aj vírusové bradavice?', 'Posúdime ich a poradíme najvhodnejší postup. Pri vírusových bradaviciach môže byť potrebné ošetrenie u lekára.'],
    ],
],

'spona.php' => [
    'active' => 'spona',
    'title'  => 'Špona na vrastajúci necht',
    'desc'   => 'Neinvazívna korekcia vrastajúceho nechta pomocou špony. Úľava od bolesti bez operácie v PediSalóne ZK.',
    'h1'     => 'Špona – vrastajúci necht',
    'lead'   => 'Neinvazívna korekcia vrastajúceho nechta, ktorá šetrne vedie necht do správneho tvaru – bez operácie.',
    'services' => ['spona'],
    'intro'  => [
        'Vrastajúci necht je bolestivý a bez ošetrenia sa môže zapáliť. Spona (špona) je tenká elastická pomôcka, ktorá sa prilepí na necht a postupne ho vedie do správneho tvaru.',
        'Metóda je šetrná, väčšinou nebolestivá a umožňuje bežné nosenie obuvi. Špona sa po čase kontroluje a podľa potreby upravuje alebo vymieňa.',
    ],
    'includes' => ['Posúdenie stavu nechta a okolia', 'Úprava nechtovej platničky', 'Aplikácia špony na mieru', 'Ošetrenie okolitej kože', 'Inštrukcie k starostlivosti', 'Plán kontrol a výmeny'],
    'steps' => [['Posúdenie', 'Zhodnotíme, či je špona vhodné riešenie.'], ['Príprava', 'Upravíme necht a okolie.'], ['Aplikácia', 'Prilepíme špongu presne na mieru.'], ['Kontrola', 'Dohodneme termín kontroly a výmeny.']],
    'tips' => ['Nechty strihajte rovno, nie do oblúka.', 'Nenoste úzku a tesnú obuv.', 'Pri začervenaní, opuchu alebo hnisaní vyhľadajte lekára.'],
    'note'  => 'Pri akútnom zápale, hnisaní alebo veľmi silnej bolesti prosím najskôr navštívte lekára. Špona je určená na korekciu bez zápalu.',
    'photos' => ['spona-1', 'spona-2', 'spona-3', 'spona-4', 'spona-5', 'spona-6'],
    'faq' => [
        ['Ako dlho špona vydrží?', 'Podľa tvaru nechta a rýchlosti rastu zvyčajne niekoľko týždňov. Pravidelne ju kontrolujeme a dolepujeme.'],
        ['Môžem po aplikácii chodiť normálne?', 'Áno, špona je tenká a pohodlná. Bežné aktivity ani nosenie topánok nebýva problém.'],
        ['Je špona vhodná pre každého?', 'Nie vždy. Pri silne zapálenom alebo hnisavom stave odporúčame lekára. Vhodnosť posúdime pri konzultácii.'],
    ],
],

];
