<?php
/** Obsah podstránok jednotlivých služieb. */
return [

'pedikura.php' => [
    'active' => 'pedikura',
    'title'  => 'Pedikúra a lakovanie',
    'desc'   => 'Pedikúra suchá, mokrá aj kombinovaná, lakovanie Gellak a Lak Moyra, kúpeľ s peelingom a maskou. Cenník a rezervácia v PediSalóne ZK.',
    'h1'     => 'Pedikúra a lakovanie',
    'lead'   => 'Kompletná starostlivosť o chodidlá, nechty a kožu – pre krásny vzhľad a pocit ľahkosti pri každom kroku.',
    'services' => ['pedikura-sucha', 'pedikura-mokra', 'pedikura-kombinovana', 'pedikura-gellak', 'pedikura-moyra', 'lakovanie-gellak', 'lakovanie-moyra', 'kupel-peeling-maska'],
    'intro'  => [
        'Pravidelná pedikúra nie je len o estetike. Správne upravené nechty, uvoľnená koža a ošetrené päty predchádzajú nepríjemným problémom a nohy sa vám poďakujú pohodlím.',
        'Pri každej návšteve si najprv prezrieme stav vašich chodidiel a podľa toho zvolíme postup. Na záver môžete nechty nechať prirodzené alebo si vybrať farbu – klasický lak či trvanlivý gél lak.',
    ],
    'includes' => ['Dezinfekcia', 'Ošetrenie zhrubnutej kože frézkou alebo skalpelom', 'Úprava nechtov', 'Krém na chodidlá', 'Voliteľne lakovanie – Gellak alebo Lak Moyra, olejček', 'Relaxačný kúpeľ, peeling a maska (samostatne)'],
    'steps' => [['Konzultácia', 'Prezrieme nohy a dohodneme očakávania.'], ['Úprava', 'Nechty, kutikuly a zrohovatená koža.'], ['Starostlivosť', 'Hydratácia a relaxačná masáž.'], ['Finálny vzhľad', 'Lak alebo gél lak podľa výberu.']],
    'tips' => ['Návštevu opakujte približne každé 4–6 týždňov.', 'Chodidlá denne hydratujte krémom s urea.', 'Noste pohodlnú obuv, ktorá netlačí na prsty.'],
    'photos' => ['pedikura-1', 'pedikura-3', 'pedikura-5', 'pedikura-7', 'pedikura-9', 'pedikura-10'],
    'faq' => [
        ['Aký je rozdiel medzi pedikúrou a pedikúrou s gél lakom?', 'Pri pedikúre s gél lakom po kompletnom ošetrení pridáme gél lak, ktorý vydrží niekoľko týždňov bez odlupovania.'],
        ['Bolí pedikúra?', 'Nie. Pracujeme šetrne a ak máte citlivé miesta, stačí nám to povedať.'],
        ['Ako dlho vydrží gél lak?', 'Zvyčajne 3–4 týždne, podľa rýchlosti rastu nechtov a starostlivosti.'],
    ],
],

'mykoza.php' => [
    'active' => 'mykoza',
    'title'  => 'Ošetrenie nechtov',
    'desc'   => 'Ošetrenie poškodeného a zarasteného nechtu, protetika (náhrada) nechtu a tamponáda v PediSalóne ZK. Ceny a rezervácia online.',
    'h1'     => 'Ošetrenie nechtov',
    'lead'   => 'Šetrné ošetrenie poškodených a zarastených nechtov, protetika nechtu a tamponáda – s dôrazom na hygienu.',
    'services' => ['poskodeny-necht', 'poskodeny-necht-pedikura', 'zarasteny-necht', 'zarasteny-necht-pedikura', 'protetika-nechtu', 'tamponada'],
    'intro'  => [
        'Poškodený, zarastený alebo inak zmenený necht dokáže znepríjemniť bežný deň aj pohodlie v obuvi. Každé ošetrenie začíname dezinfekciou a pracujeme šetrne.',
        'Ošetrenie nechtov si môžete objednať samostatne alebo ako doplnok k pedikúre – vtedy je cena výrazne nižšia.',
    ],
    'includes' => ['Dezinfekcia', 'Zbrúsenie nechtovej platničky pri poškodenom nechte', 'Odstránenie zarastených častí nechtu', 'Protetika (náhrada) nechtu – príprava platničky, nanesenie gélu, tvarovanie nechtu', 'Tamponáda – čistenie nechtu a zavedenie tamponády'],
    'steps' => [['Posúdenie', 'Pozrieme sa na stav nechtu a okolitej kože.'], ['Dezinfekcia', 'Dôkladne vydezinfikujeme ošetrovanú oblasť.'], ['Ošetrenie', 'Postup podľa typu problému.'], ['Poradenstvo', 'Poradíme, ako sa o necht starať doma.']],
    'tips' => ['Nechty strihajte rovno, nie do oblúka.', 'Nenoste úzku a tesnú obuv.', 'Pri začervenaní, opuchu alebo hnisaní vyhľadajte lekára.'],
    'note'  => 'Dôležité: Naše služby nenahrádzajú lekárske vyšetrenie ani liečbu. Pri zápale, hnisaní alebo podozrení na plesňové ochorenie odporúčame návštevu lekára.',
    'photos' => ['mykoza-1', 'mykoza-2'],
    'faq' => [
        ['Môžem si ošetrenie nechtu objednať popri pedikúre?', 'Áno. Ak ošetrenie nechtu robíme spolu s pedikúrou, platíte za neho nižšiu cenu (5 €).'],
        ['Vyliečite mykózu nechtov?', 'Nie, liečbu určuje lekár. My ponúkame šetrnú úpravu nechtov a poradenstvo v starostlivosti.'],
        ['Je ošetrenie bezpečné pre ostatných klientov?', 'Áno. Používame prísne hygienické postupy a pomôcky po každom klientovi čistíme a dezinfikujeme.'],
    ],
],
'bradavice-kurie-oka.php' => [
    'active' => 'bradavice',
    'title'  => 'Ošetrenie kuriech očí a otlakov',
    'desc'   => 'Precízne ošetrenie kurích očí a bradavíc na chodidlách v PediSalóne ZK. Šetrne a s dôrazom na komfort.',
    'h1'     => 'Kurie oká a otlaky',
    'lead'   => 'Precízne a šetrné ošetrenie problémových miest na chodidlách, ktoré vám berú pohodlie pri chôdzi.',
    'services' => ['kurie-oko', 'kurie-oko-pedikura'],
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
        'Vrastajúci necht je bolestivý a bez ošetrenia sa môže zapáliť. Spona (špona) je tenká elastická pomôcka, ktorá sa prilepí na necht a postupne ho vedie do správneho tvaru. Používame spony značky Podofix.',
        'Metóda je šetrná, väčšinou nebolestivá a umožňuje bežné nosenie obuvi. Špona sa po čase kontroluje a podľa potreby upravuje alebo vymieňa.',
    ],
    'includes' => ['Posúdenie stavu nechta a okolia', 'Úprava nechtovej platničky', 'Aplikácia spony Podofix na mieru', 'Ošetrenie okolitej kože', 'Inštrukcie k starostlivosti', 'Plán kontrol a výmeny'],
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
