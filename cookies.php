<?php
require_once __DIR__ . '/inc/lib.php';
$active = '';
$title = 'Zásady používania cookies';
$desc = 'Aké cookies a podobné technológie používa web ' . cfg()['salon']['name'] . ' a ako ich môžete spravovať.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span>Cookies</span></nav>
    <h1>Zásady používania cookies</h1>
  </div>
</section>
<section class="section">
  <div class="wrap prose" style="max-width:860px">
    <p>Cookies sú malé textové súbory, ktoré si web ukladá vo vašom prehliadači. Rovnakým spôsobom fungujú aj podobné technológie, napríklad lokálne úložisko prehliadača. Náš web ich používa čo najmenej: <b>nepoužívame analytické, reklamné ani sledovacie cookies</b> a nezdieľame údaje o vašej návšteve s reklamnými sieťami.</p>

    <h2>Aké cookies a úložiská používame</h2>
    <div class="table-wrap">
      <table class="cookie-table">
        <thead><tr><th>Názov</th><th>Účel</th><th>Platnosť</th><th>Kategória</th></tr></thead>
        <tbody>
          <tr><td><code>pz-consent</code><br><small>(lokálne úložisko)</small></td><td>Zapamätá si vašu voľbu v lište cookies, aby sme sa vás nepýtali pri každej návšteve.</td><td>Do vymazania prehliadača, najviac 12 mesiacov</td><td>Nevyhnutné (prevádzkovateľ)</td></tr>
          <tr><td><code>pedi_admin</code></td><td>Technický súbor pre prihlásenie do administrácie. Vytvára sa <b>len správcovi webu</b> po prihlásení, bežným návštevníkom sa nevytvára.</td><td>Do zatvorenia prehliadača</td><td>Nevyhnutné (prevádzkovateľ)</td></tr>
          <tr><td>Cookies Google Máp<br><small>(napr. <code>NID</code>, <code>CONSENT</code>)</small></td><td>Zobrazenie mapy na stránke Kontakt. Načíta sa <b>až po vašom súhlase</b>. Google môže po jej zobrazení spracovať vašu IP adresu a uložiť vlastné cookies.</td><td>Určuje Google</td><td>Externá služba (len so súhlasom)</td></tr>
        </tbody>
      </table>
    </div>
    <p>Na nevyhnutné cookies a úložisko sa súhlas nevyžaduje, pretože bez nich web nefunguje správne. Písma, obrázky ani skripty nenačítavame zo serverov tretích strán, všetko je uložené na našom webe.</p>

    <h2>Ako spravovať svoj súhlas</h2>
    <p>Svoju voľbu môžete kedykoľvek zmeniť alebo odvolať. Použite tlačidlo nižšie alebo odkaz „Nastavenia cookies“ v pätičke stránky.</p>
    <p><button type="button" class="btn btn-sm" data-cookie-settings>Otvoriť nastavenia cookies</button></p>
    <p>Cookies môžete spravovať a mazať aj priamo v nastaveniach vášho prehliadača. Ak ich zablokujete, niektoré funkcie (napríklad zapamätanie voľby) nemusia fungovať.</p>

    <h2>Viac informácií</h2>
    <p>Ako spracúvame osobné údaje a aké práva máte, popisujeme v <a href="ochrana-udajov.php">zásadách ochrany osobných údajov</a>. Otázky k cookies môžete poslať na <a href="mailto:<?= e($S['email']) ?>"><?= e($S['email']) ?></a>.</p>
    <p style="color:var(--muted);font-size:.9rem">Posledná aktualizácia: 22. 11. 2026</p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
