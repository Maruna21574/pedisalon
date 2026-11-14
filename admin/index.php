<?php
require __DIR__ . '/_boot.php';

$today = date('Y-m-d');
$bookings = store_read(BOOKINGS_FILE);
$pending = count(array_filter($bookings, fn($b) => ($b['status'] ?? '') === 'pending' && $b['date'] >= $today));
$todayList = array_filter($bookings, fn($b) => $b['date'] === $today && ($b['status'] ?? '') !== 'cancelled');
$upcoming = count(array_filter($bookings, fn($b) => $b['date'] >= $today && ($b['status'] ?? '') !== 'cancelled'));
$edited = count(store_read(TEXTS_FILE));
$totalTexts = count(texts_registry());
$photos = count(gallery_data()['items']);

admin_head('Prehľad', 'index', '', 'Vitajte v administrácii. Vyberte, čo chcete upraviť – všetko sa hneď prejaví na webe.');
?>
<div class="dash">
  <a class="dash-card" href="rezervacie.php">
    <span class="dash-num <?= $pending ? 'hot' : '' ?>"><?= $pending ?></span>
    <h2>Rezervácie</h2>
    <p><?= $pending ? "Čaká na potvrdenie: <b>$pending</b>." : 'Nič nečaká na potvrdenie.' ?> Dnes: <b><?= count($todayList) ?></b>, nadchádzajúce: <b><?= $upcoming ?></b>.</p>
    <span class="dash-go">Otvoriť rezervácie →</span>
  </a>
  <a class="dash-card" href="sluzby.php">
    <span class="dash-num"><?= count($C['services']) ?></span>
    <h2>Služby a ceny</h2>
    <p>Pridať alebo zmazať službu, zmeniť cenu, trvanie, popis, fotku a texty jej podstránky.</p>
    <span class="dash-go">Upraviť služby →</span>
  </a>
  <a class="dash-card" href="texty.php">
    <span class="dash-num"><?= $edited ?></span>
    <h2>Texty webu</h2>
    <p>Všetky nadpisy, odseky, tlačidlá a popisy na stránkach. Upravených: <b><?= $edited ?></b> z <?= $totalTexts ?>.</p>
    <span class="dash-go">Upraviť texty →</span>
  </a>
  <a class="dash-card" href="galeria.php">
    <span class="dash-num"><?= $photos ?></span>
    <h2>Galéria a fotky</h2>
    <p>Nahrávanie fotiek, kategórie, poradie a výber ukážok na úvodnej stránke.</p>
    <span class="dash-go">Upraviť galériu →</span>
  </a>
  <a class="dash-card" href="nastavenia.php">
    <span class="dash-num">⚙</span>
    <h2>Nastavenia</h2>
    <p>Telefón, e-mail, adresa, mapa, otváracie hodiny, pravidlá rezervácií a heslo.</p>
    <span class="dash-go">Otvoriť nastavenia →</span>
  </a>
</div>

<div class="panel">
  <h2>Najčastejšie úlohy</h2>
  <ul class="quick">
    <li><a href="sluzby.php">Zmeniť cenu alebo trvanie služby</a></li>
    <li><a href="sluzby.php">Pridať novú službu (dostane vlastnú podstránku)</a></li>
    <li><a href="galeria.php">Nahrať nové fotky do galérie</a></li>
    <li><a href="nastavenia.php#hodiny">Zmeniť otváracie hodiny</a></li>
    <li><a href="rezervacie.php#zatvorene">Zatvoriť termíny kvôli dovolenke</a></li>
    <li><a href="texty.php">Zmeniť text na úvodnej stránke, v menu alebo v pätičke</a></li>
    <li><a href="nastavenia.php#heslo">Zmeniť heslo do administrácie</a></li>
  </ul>
</div>
<?php admin_foot();
