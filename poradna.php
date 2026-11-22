<?php
$active = 'poradna';
$title = 'Poradňa – najčastejšie problémy chodidiel';
$desc = 'Poradňa PediSalónu ZK: vrastajúci necht, mykóza, kurie oká, bradavice, popraskané päty a ďalšie ťažkosti chodidiel – čo ich spôsobuje, ako sa prejavujú a čo pomáha.';
require __DIR__ . '/inc/header.php';

// Obsah poradne: id, nadpis, úvod, príčiny, príznaky, čo pomáha, odkaz na službu, kedy k lekárovi
$topics = [
    [
        'id' => 'vrastajuci-necht', 'icon' => 'nail', 'h' => 'Vrastajúci necht',
        'intro' => 'Necht sa zarezáva do okolitej kože, čo spôsobuje bolesť, začervenanie a pri neliečení aj zápal. Najčastejšie postihuje palec na nohe.',
        'causes' => ['Nesprávne strihanie nechtov – príliš nakrátko alebo do oblúka', 'Úzka, tesná alebo špicatá obuv, ktorá tlačí na prsty', 'Vrodený tvar nechta (zakrivená, tzv. klieštikovitá platnička)', 'Úraz, zatlačenie alebo opakovaná záťaž prsta (šport, dlhé státie)', 'Dedičnosť a zhrubnutie nechta po plesňovej infekcii'],
        'signs' => ['Bolesť pri tlaku alebo v obuvi', 'Začervenanie a opuch okolo nechta', 'Pri zápale hnisanie alebo zvýšená teplota kože'],
        'help' => 'V začiatkoch pomáha správna úprava nechta a neinvazívna korekcia spónou, ktorá necht postupne vedie do správneho tvaru – bez operácie.',
        'link' => ['spona.php', 'Špona na vrastajúci necht'],
        'doctor' => 'Pri hnisaní, silnej bolesti, horúčke alebo u diabetikov vždy najskôr navštívte lekára.',
    ],
    [
        'id' => 'mykoza', 'icon' => 'dot', 'h' => 'Mykóza (plesňové ochorenie nechtov a kože)',
        'intro' => 'Ide o plesňovú infekciu, ktorá zafarbuje a zhrubuje nechty alebo postihuje kožu medzi prstami a na chodidle. Je to veľmi časté a bez ošetrenia sa pomaly šíri.',
        'causes' => ['Vlhké a teplé prostredie – dlhé nosenie uzavretej obuvi, potenie nôh', 'Chodenie naboso na verejných miestach: bazény, sauny, sprchy, telocvične', 'Požičiavanie obuvi, uterákov alebo pomôcok na pedikúru', 'Poranenie nechta alebo kože, cez ktoré plesne ľahšie vniknú', 'Oslabená imunita, cukrovka, poruchy prekrvenia', 'Neúplné vyliečenie predchádzajúcej infekcie'],
        'signs' => ['Necht žltne, belie, hnedne alebo sa zakaľuje', 'Zhrubnutý, drobivý alebo odlupujúci sa necht', 'Šúpanie, svrbenie a praskliny medzi prstami', 'Nepríjemný zápach'],
        'help' => 'Odborné ošetrenie nechtov – opatrné zbrúsenie zhrubnutej platničky a poradenstvo k domácej starostlivosti. Na samotnú liečbu plesne odporúčame lekára alebo lekárnika, protiplesňové prípravky vám predpíše alebo odporučí lekár či lekárnik.',
        'link' => ['mykoza.php', 'Ošetrenie nechtov'],
        'doctor' => 'Mykózu nie je možné vyliečiť len pedikúrou. Pri podozrení na plesňovú infekciu sa poraďte s lekárom (dermatológom).',
    ],
    [
        'id' => 'kurie-oka', 'icon' => 'foot', 'h' => 'Kurie oká a mozole',
        'intro' => 'Zhrubnutá, tvrdá koža v miestach, kde sa chodidlo opakovane trie alebo tlačí. Kurie oko má tvrdé jadro, ktoré tlačí do hĺbky a môže byť veľmi bolestivé.',
        'causes' => ['Nevhodná obuv – tesná, príliš voľná alebo s tvrdými švami', 'Deformity chodidla a prstov (vybočený palec, kladivkové prsty, plochá noha)', 'Chodenie bez ponožiek alebo v ponožkách s hrubými švami', 'Nesprávne zaťažovanie chodidla pri chôdzi', 'Dlhé státie, šport, nadváha', 'Suchá koža, ktorá sa horšie prispôsobuje tlaku'],
        'signs' => ['Tvrdý, žltkastý hrubý bod s jadrom (kurie oko)', 'Plošne zhrubnutá koža (mozol)', 'Bolesť pri každom kroku alebo pri tlaku obuvi'],
        'help' => 'Odborné odstránenie zrohovatenej kože a jadra kuriaho oka, úprava tlakových miest a rady k vhodnej obuvi. Ošetrenie prináša rýchlu úľavu.',
        'link' => ['bradavice-kurie-oka.php', 'Kurie oká a otlaky'],
        'doctor' => 'Kurie oká nikdy nevyrezávajte doma žiletkou. Pri diabete alebo poruchách prekrvenia sa poraďte s lekárom.',
    ],
    [
        'id' => 'bradavice', 'icon' => 'sparkle', 'h' => 'Bradavice na chodidle',
        'intro' => 'Vyvolávajú ich vírusy (HPV). Na chodidle bývajú zatlačené dovnútra, preto sa podobajú na kurie oká, no mávajú drobné tmavé bodky a bolia aj pri stlačení zo strán.',
        'causes' => ['Vírusová infekcia (ľudský papilomavírus) – prenáša sa kontaktom', 'Chodenie naboso v bazénoch, saunách, sprchách a šatniach', 'Drobné poranenia alebo macerovaná (rozmočená) koža, cez ktorú vírus vniká', 'Zníženej obranyschopnosti organizmu', 'Spoločné používanie uterákov, obuvi a pomôcok'],
        'signs' => ['Tvrdý výrastok s drobnými tmavými bodkami', 'Bolesť pri stlačení zo strán, nielen zhora', 'Prerušené línie kožnej kresby v mieste bradavice'],
        'help' => 'Odborné posúdenie, či ide o bradavicu alebo kurie oko, a poradenstvo, ako zabrániť šíreniu na ďalšie miesta. Samotné bradavice je vhodné riešiť s lekárom.',
        'link' => ['bradavice-kurie-oka.php', 'Kurie oká a otlaky'],
        'doctor' => 'Bradavice sú infekčné. Pri viacerých, rýchlo sa šíriacich alebo bolestivých bradaviciach vyhľadajte lekára.',
    ],
    [
        'id' => 'popraskane-paty', 'icon' => 'heel', 'h' => 'Popraskané päty a suchá koža',
        'intro' => 'Zrohovatená koža päty stratí pružnosť a začne praskať. Praskliny môžu byť hlboké, krvácať a bolieť, hlavne v lete a v otvorenej obuvi.',
        'causes' => ['Nedostatok hydratácie – suchá koža, nedostatok krémov na nohy', 'Otvorená obuv, sandále a chodenie naboso', 'Dlhé státie a nadmerný tlak na pätu (nadváha)', 'Vplyv horúcej vody a agresívnych mydiel', 'Plesňová infekcia kože (tzv. mokasínový typ)', 'Cukrovka, ochorenie štítnej žľazy, nedostatok vitamínov', 'Vek – koža s vekom vysychá'],
        'signs' => ['Zhrubnutá, žltkastá koža na päte', 'Plytké alebo hlboké praskliny, niekedy krvácanie', 'Pálenie a bolesť pri chôdzi'],
        'help' => 'Pedikúra (suchá prístrojová alebo mokrá skalpelová) zbaví chodidlo zrohovatenej kože a ošetrenie krémom zmäkčí pokožku. Dôležitá je následná domáca starostlivosť s krémom s močovinou (urea).',
        'link' => ['pedikura.php', 'Pedikúra a lakovanie'],
        'doctor' => 'Hlboké, krvácajúce alebo zapálené praskliny a praskliny u diabetikov patria k lekárovi.',
    ],
    [
        'id' => 'potenie-zapach', 'icon' => 'leaf', 'h' => 'Potenie nôh a zápach',
        'intro' => 'Chodidlá majú množstvo potných žliaz. Nadmerné potenie spolu s vlhkým prostredím v obuvi vytvára podmienky pre baktérie, ktoré spôsobujú zápach.',
        'causes' => ['Syntetická obuv a ponožky, ktoré neprepúšťajú vzduch', 'Dlhé nosenie tej istej obuvi bez vysušenia', 'Stres a hormonálne zmeny', 'Nadmerné potenie (hyperhidróza)', 'Plesne a baktérie na koži a v obuvi'],
        'signs' => ['Vlhké chodidlá a ponožky', 'Zápach z nôh a obuvi', 'Zmäknutá, zbelavá koža, ktorá sa ľahšie infikuje'],
        'help' => 'Pravidelné ošetrenie chodidiel a poradenstvo – prirodzené materiály, striedanie obuvi, dôkladné sušenie nôh a vhodné prípravky.',
        'link' => ['pedikura.php', 'Pedikúra a lakovanie'],
        'doctor' => 'Ak je potenie extrémne a výrazne obmedzuje bežný život, poraďte sa s lekárom.',
    ],
];
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span>Poradňa</span></nav>
    <h1 class="reveal">Poradňa</h1>
    <p class="reveal" style="--d:.1s">Najčastejšie ťažkosti chodidiel jednoducho a zrozumiteľne – čo ich spôsobuje, ako sa prejavujú a čo pomáha.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="advice-intro reveal">
      <p>Zdravé chodidlá sú základ pohody pri každom kroku. Mnohé problémy sa dajú riešiť jednoducho, ak ich spoznáte včas. Nižšie nájdete prehľad najčastejších ťažkostí – od vrastajúceho nechta až po popraskané päty.</p>
      <div class="note"><strong>Dôležité:</strong> Text má informatívny charakter a nenahrádza lekársku diagnostiku ani liečbu. Pedikúra je kozmetické ošetrenie. Pri podozrení na ochorenie, zápal, hnisanie alebo ak máte cukrovku či poruchy prekrvenia, vždy sa poraďte s lekárom.</div>
    </div>

    <nav class="advice-nav reveal" aria-label="Rýchly výber témy">
      <?php foreach ($topics as $t): ?><a href="#<?= e($t['id']) ?>"><?= icon($t['icon']) ?> <?= e($t['h']) ?></a><?php endforeach; ?>
    </nav>

    <div class="advice-list">
      <?php foreach ($topics as $t): ?>
      <article class="advice reveal" id="<?= e($t['id']) ?>">
        <header>
          <span class="advice-ico"><?= icon($t['icon']) ?></span>
          <div>
            <h2><?= e($t['h']) ?></h2>
            <p><?= e($t['intro']) ?></p>
          </div>
        </header>
        <div class="advice-cols">
          <div>
            <h3>Čo to spôsobuje</h3>
            <ul class="ticks"><?php foreach ($t['causes'] as $c): ?><li><?= icon('check') ?> <?= e($c) ?></li><?php endforeach; ?></ul>
          </div>
          <div>
            <h3>Ako sa to prejavuje</h3>
            <ul class="ticks"><?php foreach ($t['signs'] as $c): ?><li><?= icon('check') ?> <?= e($c) ?></li><?php endforeach; ?></ul>
            <h3>Čo pomáha</h3>
            <p><?= e($t['help']) ?></p>
          </div>
        </div>
        <div class="note"><?= e($t['doctor']) ?></div>
        <a href="<?= e($t['link'][0]) ?>" class="btn btn-ghost btn-sm"><?= e($t['link'][1]) ?> <?= icon('arrow') ?></a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker">Prevencia</span><h2>Ako sa starať o nohy doma</h2></div>
    <div class="features">
      <div class="feature"><div class="ic"><?= icon('shield') ?></div><h3>Správna obuv</h3><p>Dostatočne priestranná, z prírodných materiálov. Obuv pravidelne striedajte a nechajte ju vyschnúť.</p></div>
      <div class="feature"><div class="ic"><?= icon('heart') ?></div><h3>Hydratácia</h3><p>Po umytí nohy poriadne osušte, najmä medzi prstami, a každý deň ich natrite vhodným krémom.</p></div>
      <div class="feature"><div class="ic"><?= icon('nail') ?></div><h3>Správne strihanie</h3><p>Nechty strihajte rovno, nie do oblúka, a nie príliš nakrátko. Rohy nechajte nezarezané.</p></div>
      <div class="feature"><div class="ic"><?= icon('sparkle') ?></div><h3>Hygiena</h3><p>Na verejných miestach nechoďte naboso, používajte vlastné uteráky a obuv a meňte ponožky.</p></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker">Bezpečnosť</span><h2>Kedy ísť k lekárovi</h2></div>
    <div class="faq reveal">
      <details open><summary>Hnisanie, silné začervenanie alebo opuch</summary><div class="ans">Ide o príznaky zápalu. Ošetrenie v salóne nie je vhodné – vyhľadajte lekára.</div></details>
      <details><summary>Cukrovka, poruchy prekrvenia alebo neuropatia</summary><div class="ans">Pri diabetickom chodidle a zníženom prekrvení môže aj drobná ranka viesť ku komplikáciám. Bežné ošetrenie konzultujte s lekárom.</div></details>
      <details><summary>Rana, ktorá sa nehojí, alebo nezvyčajná zmena kože</summary><div class="ans">Nehojaca sa rana, tmavá škvrna alebo zmena materského znamienka patrí na vyšetrenie k lekárovi.</div></details>
      <details><summary>Plesňová infekcia, ktorá sa vracia</summary><div class="ans">Ak sa mykóza opakuje, je potrebná odborná liečba. Pedikúra ju sama nevylieči, ale vie ošetriť nechty a pomôcť s prevenciou.</div></details>
    </div>
    <p style="text-align:center;margin-top:44px" class="reveal"><a href="rezervacia.php" class="btn btn-lg"><?= t('nav.book') ?> <?= icon('arrow') ?></a></p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
