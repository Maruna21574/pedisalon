<?php
/**
 * Register upraviteľných textov webu.
 * stránka => [ sekcia => [ kľúč => [popis, predvolený text, typ] ] ]
 * Typy: text (jeden riadok), area (viac riadkov), rich (odseky; "## Nadpis"; **tučné**),
 *       lines (jedna položka na riadok), pairs ("Nadpis | text" na riadok), image (fotka z knižnice).
 * V texte možno použiť {name}, {phone}, {email}, {address}, {ico} – doplnia sa z nastavení salónu.
 * Texty jednotlivých služieb (podstránky, ceny, popisy) sa upravujú v sekcii Služby.
 */
return [

'Celý web' => [
  'Menu a tlačidlá' => [
    'nav.home'     => ['Menu – Domov', 'Domov', 'text'],
    'nav.services' => ['Menu – Služby', 'Služby', 'text'],
    'nav.pricing'  => ['Menu – Cenník', 'Cenník', 'text'],
    'nav.gallery'  => ['Menu – Galéria', 'Galéria', 'text'],
    'nav.about'    => ['Menu – O nás', 'O nás', 'text'],
    'nav.about.sub' => ['Menu O nás – 1. položka rozbaľovacieho menu', 'Náš príbeh', 'text'],
    'nav.premises' => ['Menu O nás – 2. položka (stránka Prevádzka)', 'Prevádzka', 'text'],
    'nav.contact'  => ['Menu – Kontakt', 'Kontakt', 'text'],
    'nav.book'     => ['Tlačidlo „Rezervovať termín“ (menu aj ostatné tlačidlá)', 'Rezervovať termín', 'text'],
    'nav.allservices' => ['Menu služieb – posledný odkaz', 'Všetky služby →', 'text'],
    'fab.book'     => ['Plávajúce tlačidlo vpravo dole', 'Rezervovať', 'text'],
    'skip'         => ['Skrytý odkaz pre čítačky obrazovky', 'Preskočiť na obsah', 'text'],
    'common.from'  => ['Slovo pred cenou („od 25 €“)', 'od', 'text'],
    'common.min'   => ['Skratka minút', 'min', 'text'],
  ],
  'Výzva nad pätičkou' => [
    'cta.title'    => ['Nadpis', 'Doprajte svojim nohám starostlivosť, ktorú si zaslúžia', 'text'],
    'cta.text'     => ['Text', 'Objednajte sa online za pár sekúnd – vyberiete si službu, deň aj čas.', 'text'],
  ],
  'Pätička' => [
    'footer.about' => ['Text pod sloganom', 'Profesionálna pedikúra a ošetrenie problémových chodidiel v príjemnom prostredí.', 'area'],
    'footer.h.services' => ['Nadpis stĺpca 1', 'Služby', 'text'],
    'footer.h.salon'    => ['Nadpis stĺpca 2', 'Salón', 'text'],
    'footer.h.contact'  => ['Nadpis stĺpca 3', 'Kontakt', 'text'],
    'footer.l.booking'  => ['Odkaz na rezerváciu', 'Online rezervácia', 'text'],
    'footer.l.privacy'  => ['Odkaz na ochranu údajov', 'Ochrana osobných údajov', 'text'],
    'footer.rights'     => ['Autorské práva (za rokom a názvom salónu)', 'Všetky práva vyhradené.', 'text'],
    'footer.legal'      => ['Upozornenie dole', 'Informácie na webe majú informatívny charakter a nenahrádzajú lekárske vyšetrenie.', 'text'],
  ],
  'E-maily zákazníkom' => [
    'mail.confirmed' => ['E-mail po potvrdení rezervácie. Značky: {client} meno, {service} služba, {when} termín, {name}, {phone}', "Dobrý deň {client},\n\nVaša rezervácia ({service}, {when}) je potvrdená. Tešíme sa na Vás!\n\n{name}", 'rich'],
    'mail.received' => ['E-mail hneď po odoslaní rezervácie. Značky: {client}, {service}, {when}, {address}, {phone}, {name}, {status}', "Dobrý deň {client},

ďakujeme za Vašu rezerváciu v salóne {name}.

Služba: {service}
Termín: {when}
Adresa: {address}

{status}

Ak potrebujete termín zmeniť alebo zrušiť, zavolajte na {phone}.

Tešíme sa na Vás!
{name}", 'rich'],
    'mail.status.pending' => ['{status} v e-maile – rezervácia čaká na potvrdenie', 'Rezerváciu Vám čoskoro potvrdíme telefonicky alebo e-mailom.', 'text'],
    'mail.status.confirmed' => ['{status} v e-maile – rezervácia je rovno potvrdená', 'Rezervácia je potvrdená.', 'text'],
    'mail.cancelled' => ['E-mail po zrušení rezervácie (rovnaké značky)', "Dobrý deň {client},\n\nVaša rezervácia ({service}, {when}) bola zrušená. Ak si želáte nový termín, objednajte sa na webe alebo nám zavolajte na {phone}.\n\n{name}", 'rich'],
  ],
],

'Úvodná stránka' => [
  'Úvod (veľký titulok)' => [
    'home.eyebrow'  => ['Malý nadpis nad titulkom', 'Salón pedikúry a ošetrenia chodidiel', 'text'],
    'home.h1'       => ['Hlavný titulok', 'Krásne a zdravé nohy', 'text'],
    'home.h1script' => ['Titulok – ozdobný riadok', 's láskou a precíznosťou', 'text'],
    'home.lead'     => ['Úvodný odsek', 'V **{name}** sa o vaše chodidlá postaráme šetrne a profesionálne – od pedikúry a lakovania cez ošetrenie nechtov a kurích očí až po špony na vrastajúci necht.', 'area'],
    'home.btn.services' => ['Druhé tlačidlo', 'Naše služby', 'text'],
    'home.badges'   => ['Tri odznaky pod tlačidlami (jeden riadok = jeden odznak)', "Dôraz na hygienu\nIndividuálny prístup\nOnline rezervácia 24/7", 'lines'],
    'home.hero.img' => ['Fotka v úvodnom oválnom okne', 'hero-detail.jpg', 'image'],
    'home.chip1'    => ['Bublina 1 pri fotke', 'Rezervácia online 24/7', 'text'],
    'home.chip2'    => ['Bublina 2 pri fotke', 'Šetrné ošetrenie', 'text'],
    'home.marquee'  => ['Bežiaci pás so službami (jeden riadok = jedna položka)', "Pedikúra suchá, mokrá, kombinovaná\nLakovanie Gellak a Lak Moyra\nOšetrenie nechtov\nKurie oká a otlaky\nReflexná masáž chodidiel\nŠpona Podofix", 'lines'],
  ],
  'Služby' => [
    'home.services.kicker' => ['Malý nadpis', 'Čo pre vás urobíme', 'text'],
    'home.services.title'  => ['Nadpis', 'Naše služby', 'text'],
    'home.services.text'   => ['Popis', 'Od relaxačnej pedikúry po ošetrenie problémových chodidiel. Vyberte si, čo vaše nohy potrebujú.', 'area'],
    'home.card.more'       => ['Odkaz na kartách služieb', 'Zistiť viac', 'text'],
    'home.prices.btn'      => ['Tlačidlo pod kartami', 'Pozrieť celý cenník', 'text'],
  ],
  'O salóne' => [
    'home.about.kicker' => ['Malý nadpis', 'O salóne', 'text'],
    'home.about.title'  => ['Nadpis', 'Starostlivosť, ktorú vaše nohy ocenia', 'text'],
    'home.about.text'   => ['Text', 'Nohy nás nesú celý deň – zaslúžia si pozornosť. V našom salóne sa venujeme estetickej aj problémovej pedikúre. Každé ošetrenie začíname krátkou konzultáciou, aby sme zvolili postup šetrný práve pre vás.', 'area'],
    'home.about.ticks'  => ['Odrážky (jeden riadok = jedna odrážka)', "Šetrné postupy a kvalitná kozmetika\nČisté pomôcky a príjemné prostredie\nPoradenstvo ako sa starať o nohy doma\nPohodlné objednanie online", 'lines'],
    'home.about.btn'    => ['Tlačidlo', 'Viac o nás', 'text'],
    'home.about.img1'   => ['Veľká fotka', 'pedikura-6.jpg', 'image'],
    'home.about.img2'   => ['Malá fotka', 'pedikura-9.jpg', 'image'],
    'home.about.badge'  => ['Odznak na fotke – nadpis', 'S láskou', 'text'],
    'home.about.badge2' => ['Odznak na fotke – podpis', 'ku každému klientovi', 'text'],
  ],
  'Ako to prebieha' => [
    'home.flow.kicker'  => ['Malý nadpis', 'Ako to prebieha', 'text'],
    'home.flow.title'   => ['Nadpis', 'Štyri jednoduché kroky k zdravším nohám', 'text'],
    'home.flow.steps'   => ['Kroky (jeden riadok = „Nadpis | text“)', "Rezervácia | Vyberiete si službu a voľný termín v online kalendári.\nKonzultácia | Pozrieme sa na stav chodidiel a dohodneme vhodný postup.\nOšetrenie | Starostlivo a šetrne sa postaráme o vaše nohy.\nRady domov | Dostanete tipy, ako výsledok udržať čo najdlhšie.", 'pairs'],
  ],
  'Recenzie z Google' => [
    'home.reviews.kicker' => ['Malý nadpis', 'Čo hovoria klienti', 'text'],
    'home.reviews.title'  => ['Nadpis', 'Recenzie na Google', 'text'],
    'home.reviews.cta'    => ['Tlačidlo „Zobraziť všetky recenzie“', 'Zobraziť všetky recenzie', 'text'],
  ],
  'Ukážky prác a otázky' => [
    'home.work.kicker'  => ['Ukážky – malý nadpis', 'Z nášho salónu', 'text'],
    'home.work.title'   => ['Ukážky – nadpis (fotky určíte v sekcii Galéria)', 'Ukážky našej práce', 'text'],
    'home.work.btn'     => ['Ukážky – tlačidlo', 'Celá galéria', 'text'],
    'home.faq.kicker'   => ['Otázky – malý nadpis', 'Máte otázky?', 'text'],
    'home.faq.title'    => ['Otázky – nadpis', 'Časté otázky', 'text'],
    'faq.general'       => ['Časté otázky (jeden riadok = „Otázka | odpoveď“)', "Ako sa môžem objednať? | Najjednoduchšie cez online rezerváciu na tomto webe – vyberiete službu, deň a čas. Môžete nám aj zavolať.\nAko dlho trvá pedikúra? | Pedikúra (suchá, mokrá aj kombinovaná) trvá približne 60 minút, pedikúra s lakom (Gellak alebo Lak Moyra) asi 90 minút. Trvanie ostatných služieb nájdete v cenníku.\nČo mám priniesť so sebou? | Nič špeciálne. Stačí pohodlná obuv, a ak chcete Gellak alebo Lak Moyra, odtieň si vyberiete u nás.\nMôžem prísť, ak mám plesňové ochorenie alebo ranky? | Áno, ale prosím upozornite nás vopred pri rezervácii. Pri akútnom zápale, hnisavých alebo nehojacich sa ranách vám odporúčame najskôr navštíviť lekára.\nAko zrušiť alebo zmeniť rezerváciu? | Zavolajte nám alebo napíšte e-mail, najlepšie aspoň 24 hodín vopred, aby sme termín mohli ponúknuť ďalším klientom.\nAko platím? | Platba prebieha priamo v salóne po ošetrení. Podrobnosti o platobných možnostiach vám radi povieme telefonicky.", 'pairs'],
  ],
],

'O nás' => [
  'Hlavička' => [
    'about.hero.title' => ['Nadpis stránky', 'O nás', 'text'],
    'about.hero.lead'  => ['Podtitulok stránky', 'Za každým pekným krokom stojí zdravé a upravené chodidlo.', 'text'],
  ],
  'Náš príbeh' => [
    'about.kicker'     => ['Malý nadpis', 'Náš príbeh', 'text'],
    'about.title'      => ['Nadpis', 'Vitajte v PediSalóne ZK', 'text'],
    'about.body'       => ['Text (odseky oddeľte prázdnym riadkom)', "Náš salón vznikol z jednoduchého presvedčenia: **nohy si zaslúžia rovnakú starostlivosť ako tvár či ruky**. Venujeme sa nielen estetickej pedikúre, ale aj problémom, s ktorými sa ľudia často boja ísť – mozole, kurie oká, zhrubnuté nechty alebo vrastajúci necht.\n\nLogo salóna – chodidlo so srdiečkom a lístkami – vyjadruje naše hodnoty: **láska** k práci, **prírodná** šetrnosť a **precíznosť** v každom detaile.\n\nPracujeme v pokojnom, čistom prostredí. Pri každej návšteve si vyhradíme čas, aby sme sa mohli venovať práve vám.", 'rich'],
    'about.btn'        => ['Tlačidlo', 'Objednať sa', 'text'],
    'about.img'        => ['Vaša fotka pri texte (kým nevyberiete fotku, zobrazuje sa zástupný rámik „Vaša fotka“)', 'majitelka-portret.jpg', 'image'],
  ],
  'Hodnoty' => [
    'about.values.kicker' => ['Malý nadpis', 'Naše hodnoty', 'text'],
    'about.values.title'  => ['Nadpis', 'Na čom si zakladáme', 'text'],
    'about.values'     => ['Hodnoty (jeden riadok = „Nadpis | text“)', "Hygiena | Čisté pomôcky, upratané prostredie a prísne postupy pri každom klientovi.\nEmpatia | Chápeme, že niektoré problémy sú citlivé. U nás sa nemusíte hanbiť.\nŠetrnosť | Jemné postupy a kvalitná kozmetika, ktoré vaše nohy nepodráždia.\nPrecíznosť | Ku každému ošetreniu pristupujeme s pozornosťou k detailom.", 'pairs'],
  ],
],

'Prevádzka' => [
  'Hlavička' => [
    'premises.hero.title' => ['Nadpis stránky', 'Naša prevádzka', 'text'],
    'premises.hero.lead'  => ['Podtitulok', 'Nahliadnite do salónu ešte pred prvou návštevou – čisté, pokojné a príjemné prostredie.', 'text'],
  ],
  'Úvod a fotky' => [
    'premises.kicker' => ['Malý nadpis', 'Kde sa o vás staráme', 'text'],
    'premises.title'  => ['Nadpis', 'Príjemné prostredie, v ktorom sa oddýchnete', 'text'],
    'premises.body'   => ['Text (odseky oddeľte prázdnym riadkom)', "Náš salón sme zariadili tak, aby ste sa v ňom cítili pohodlne od prvej minúty. Pracujeme v pokojnom a upratanom prostredí, kde máte dostatok súkromia aj času.

Každé pracovisko dezinfikujeme a používame čisté pomôcky, aby ste sa mohli uvoľniť a vychutnať si starostlivosť o svoje nohy.", 'rich'],
    'premises.photos' => ['Fotky prevádzky (kým nevyberiete fotky, zobrazujú sa zástupné rámiky)', "salon-pracovisko-1.jpg
salon-cakaren.jpg
salon-pracovisko-2.jpg
salon-miestnost.jpg
salon-sterilizacia.jpg
salon-vybavenie.jpg
salon-ulica.jpg
salon-vchod.jpg", 'photos'],
  ],
  'Čo vás čaká' => [
    'premises.features.title' => ['Nadpis', 'Čo vás u nás čaká', 'text'],
    'premises.features' => ['Výhody (jeden riadok = „Nadpis | text“)', "Čistota a hygiena | Upratané pracovisko a starostlivo pripravené pomôcky pre každého klienta.
Pokojná atmosféra | Pokojné prostredie bez zhonu, v ktorom si oddýchnete.
Pohodlie | Príjemné sedenie a dostatok priestoru na úplné uvoľnenie.
Súkromie | Venujeme sa vám individuálne, bez pocitu, že sa niekam ponáhľate.", 'pairs'],
    'premises.visit.title' => ['Kde nás nájdete – nadpis', 'Navštívte nás', 'text'],
    'premises.visit.text'  => ['Kde nás nájdete – text ({address} = adresa salónu)', 'Nájdete nás na adrese {address}. Pred návštevou sa objednajte online alebo nám zavolajte na {phone}.', 'area'],
    'premises.visit.btn'   => ['Kde nás nájdete – tlačidlo', 'Zobraziť kontakt a mapu', 'text'],
  ],
],

'Služby a cenník' => [
  'Stránka Služby' => [
    'sluzby.hero.title' => ['Nadpis stránky', 'Naše služby', 'text'],
    'sluzby.hero.lead'  => ['Podtitulok', 'Od estetickej pedikúry po ošetrenie problémových chodidiel – všetko na jednom mieste.', 'text'],
    'sluzby.card.more'  => ['Odkaz na kartách', 'Detail', 'text'],
    'sluzby.help.kicker' => ['Spodná sekcia – malý nadpis', 'Nie ste si istí?', 'text'],
    'sluzby.help.title'  => ['Spodná sekcia – nadpis', 'Poradíme vám', 'text'],
    'sluzby.help.text'   => ['Spodná sekcia – text', 'Ak neviete, ktorá služba je pre vás tá pravá, vyberte pri rezervácii kompletnú pedikúru a v poznámke nám napíšte, čo vás trápi. Postup upravíme na mieru.', 'area'],
  ],
  'Stránka Cenník' => [
    'cennik.hero.title' => ['Nadpis stránky', 'Cenník', 'text'],
    'cennik.hero.lead'  => ['Podtitulok', 'Prehľadné ceny podľa cenníka platného od 1. 4. 2026. Ošetrenie nechtu popri pedikúre je výrazne lacnejšie.', 'text'],
    'cennik.note'       => ['Poznámka pod cenami', 'Ceny platia od 1. 4. 2026. Platba prebieha v salóne po ošetrení.', 'area'],
    'cennik.btn'        => ['Tlačidlo', 'Objednať sa online', 'text'],
  ],
  'Podstránky služieb (spoločné nadpisy)' => [
    'svcpage.h.about'    => ['Nadpis „O službe“', 'O službe', 'text'],
    'svcpage.h.includes' => ['Nadpis „Čo zahŕňa“', 'Čo zahŕňa', 'text'],
    'svcpage.h.steps'    => ['Nadpis „Ako to prebieha“', 'Ako to prebieha', 'text'],
    'svcpage.h.tips'     => ['Nadpis „Tipy“', 'Tipy pre vaše nohy', 'text'],
    'svcpage.h.faq'      => ['Nadpis „Časté otázky“', 'Časté otázky', 'text'],
    'svcpage.duration'   => ['Bočná karta – trvanie ({min} = počet minút)', 'Trvanie cca {min} minút', 'text'],
    'svcpage.hygiene'    => ['Bočná karta – 1. výhoda', 'Hygienické pomôcky', 'text'],
    'svcpage.care'       => ['Bočná karta – 2. výhoda', 'Šetrný prístup', 'text'],
    'svcpage.btn'        => ['Bočná karta – tlačidlo', 'Rezervovať', 'text'],
    'svcpage.call'       => ['Bočná karta – výzva na telefonát', 'Alebo zavolajte:', 'text'],
  ],
],

'Galéria' => [
  'Galéria' => [
    'galeria.hero.title' => ['Nadpis stránky', 'Galéria', 'text'],
    'galeria.hero.lead'  => ['Podtitulok', 'Pozrite sa na výsledky našej práce. Kliknutím fotku zväčšíte.', 'text'],
    'galeria.all'        => ['Tlačidlo filtra „všetko“', 'Všetko', 'text'],
  ],
],

'Kontakt' => [
  'Hlavička a údaje' => [
    'kontakt.hero.title' => ['Nadpis stránky', 'Kontakt', 'text'],
    'kontakt.hero.lead'  => ['Podtitulok', 'Radi vám odpovieme na otázky. Ozvite sa nám alebo sa rovno objednajte online.', 'text'],
    'kontakt.l.phone'    => ['Popis – telefón', 'Telefón', 'text'],
    'kontakt.l.email'    => ['Popis – e-mail', 'E-mail', 'text'],
    'kontakt.l.address'  => ['Popis – adresa', 'Adresa', 'text'],
    'kontakt.l.hours'    => ['Popis – otváracie hodiny', 'Otváracie hodiny', 'text'],
    'kontakt.closed'     => ['Slovo pre zatvorený deň', 'Zatvorené', 'text'],
    'kontakt.map'        => ['Odkaz pod mapou', 'Otvoriť v Mapách Google →', 'text'],
  ],
  'Kontaktný formulár' => [
    'kontakt.form.title'   => ['Nadpis formulára', 'Napíšte nám', 'text'],
    'kontakt.form.name'    => ['Popis poľa – meno', 'Meno', 'text'],
    'kontakt.form.phone'   => ['Popis poľa – telefón', 'Telefón', 'text'],
    'kontakt.form.email'   => ['Popis poľa – e-mail', 'E-mail', 'text'],
    'kontakt.form.message' => ['Popis poľa – správa', 'Správa', 'text'],
    'kontakt.form.consent' => ['Súhlas – text pred odkazom', 'Súhlasím so spracovaním osobných údajov podľa', 'text'],
    'kontakt.form.consentlink' => ['Súhlas – text odkazu', 'zásad ochrany údajov', 'text'],
    'kontakt.form.btn'     => ['Tlačidlo odoslať', 'Odoslať správu', 'text'],
    'kontakt.form.ok'      => ['Hláška po odoslaní', 'Ďakujeme, správu sme prijali. Ozveme sa vám čo najskôr.', 'text'],
  ],
],

'Online rezervácia' => [
  'Hlavička a kroky' => [
    'rezervacia.hero.title' => ['Nadpis stránky', 'Online rezervácia', 'text'],
    'rezervacia.hero.lead'  => ['Podtitulok', 'Vyberte si službu, deň a čas. Rezervácia trvá menej ako minútu.', 'text'],
    'rez.steps'     => ['Názvy 4 krokov (jeden riadok = jeden krok)', "Služba\nDátum\nČas\nÚdaje", 'lines'],
    'rez.h.service' => ['Krok 1 – nadpis', 'Vyberte službu', 'text'],
    'rez.h.date'    => ['Krok 2 – nadpis', 'Vyberte dátum', 'text'],
    'rez.legend.free' => ['Krok 2 – legenda: voľné', 'voľné termíny', 'text'],
    'rez.legend.busy' => ['Krok 2 – legenda: obsadené', 'obsadené / zatvorené', 'text'],
    'rez.h.time'    => ['Krok 3 – nadpis', 'Vyberte čas', 'text'],
    'rez.back'      => ['Tlačidlo späť', '‹ Späť', 'text'],
  ],
  'Formulár s údajmi' => [
    'rez.h.data'    => ['Krok 4 – nadpis', 'Vaše údaje', 'text'],
    'rez.f.name'    => ['Popis poľa – meno', 'Meno a priezvisko *', 'text'],
    'rez.f.phone'   => ['Popis poľa – telefón', 'Telefón *', 'text'],
    'rez.f.email'   => ['Popis poľa – e-mail', 'E-mail (pre potvrdenie)', 'text'],
    'rez.f.note'    => ['Popis poľa – poznámka', 'Poznámka (nepovinné)', 'text'],
    'rez.f.noteph'  => ['Poznámka – ukážkový text v poli', 'Napr. zdravotné ťažkosti, alergie, preferovaný odtieň lakovania…', 'text'],
    'rez.consent'   => ['Súhlas – text pred odkazom', 'Súhlasím so spracovaním osobných údajov na účely rezervácie podľa', 'text'],
    'rez.consentlink' => ['Súhlas – text odkazu', 'zásad ochrany údajov', 'text'],
    'rez.submit'    => ['Tlačidlo potvrdiť', 'Potvrdiť rezerváciu', 'text'],
  ],
  'Zhrnutie a potvrdenie' => [
    'rez.sum.title'   => ['Zhrnutie – nadpis', 'Vaša rezervácia', 'text'],
    'rez.sum.service' => ['Zhrnutie – služba', 'Služba', 'text'],
    'rez.sum.when'    => ['Zhrnutie – termín', 'Termín', 'text'],
    'rez.sum.dur'     => ['Zhrnutie – trvanie', 'Trvanie', 'text'],
    'rez.sum.price'   => ['Zhrnutie – cena', 'Cena od', 'text'],
    'rez.sum.unset'   => ['Zhrnutie – zatiaľ nevybraté', 'Zatiaľ nevybraté', 'text'],
    'rez.sum.note'    => ['Zhrnutie – poznámka dole', 'Platba prebieha v salóne. Rezerváciu môžete zrušiť telefonicky na **{phone}**.', 'area'],
    'rez.done.title'  => ['Po odoslaní – nadpis', 'Ďakujeme, rezervácia prijatá!', 'text'],
    'rez.done.confirmed' => ['Po odoslaní – ak je termín potvrdený hneď', 'Váš termín je potvrdený.', 'text'],
    'rez.done.pending'   => ['Po odoslaní – ak čaká na potvrdenie', 'Váš termín sme zaevidovali. Čoskoro vám ho potvrdíme telefonicky alebo e-mailom.', 'text'],
    'rez.done.note'   => ['Po odoslaní – poznámka dole ({phone} = telefón salónu)', 'V prípade zmeny nás prosím kontaktujte na {phone}.', 'text'],
    'rez.done.btn'    => ['Po odoslaní – tlačidlo', 'Späť na úvod', 'text'],
  ],
],

'Ochrana údajov a chybová stránka' => [
  'Ochrana osobných údajov' => [
    'privacy.body' => ['Celý text stránky (odseky oddeľte prázdnym riadkom, nadpis začnite „## “)', "*Vzor – pred zverejnením ho nechajte skontrolovať a doplňte údaje prevádzkovateľa.*\n\n## Prevádzkovateľ\n\n{name}, {address}, e-mail: {email}, tel.: {phone}. {ico}\n\n## Aké údaje spracúvame a prečo\n\nPri online rezervácii a kontaktnom formulári spracúvame meno, telefónne číslo, e-mail a poznámku, ktorú nám uvediete. Údaje používame výlučne na vybavenie rezervácie, komunikáciu s vami a organizáciu prevádzky salóna.\n\n## Právny základ a doba uchovávania\n\nSpracúvanie je nevyhnutné na plnenie zmluvy (rezervácia služby) a na základe vášho súhlasu. Údaje o rezervácii uchovávame po dobu nevyhnutnú na poskytnutie služby, najviac 12 mesiacov, pokiaľ právny predpis neurčuje inak.\n\n## Komu údaje sprístupňujeme\n\nÚdaje nepredávame ani neposkytujeme tretím stranám. Technicky ich spracúva poskytovateľ webhostingu.\n\n## Vaše práva\n\nMáte právo na prístup k údajom, ich opravu, vymazanie, obmedzenie spracúvania, prenosnosť a právo namietať. Kedykoľvek môžete odvolať súhlas. Žiadosti posielajte na {email}. Máte tiež právo podať sťažnosť Úradu na ochranu osobných údajov SR.\n\n## Cookies a externé služby\n\nWeb nepoužíva marketingové ani analytické cookies. Písma sa načítavajú zo služby Google Fonts a 3D animácia z verejnej knižnice cdnjs.", 'rich'],
  ],
  'Stránka „nenájdené“ (404)' => [
    'e404.title' => ['Titulok v karte prehliadača', 'Stránka sa nenašla', 'text'],
    'e404.text'  => ['Text', 'Táto stránka sa stratila ako ponožka v práčke. Vráťte sa na úvod alebo sa objednajte.', 'area'],
    'e404.btn1'  => ['Tlačidlo 1', 'Na úvodnú stránku', 'text'],
    'e404.btn2'  => ['Tlačidlo 2', 'Rezervácia', 'text'],
  ],
],

];
