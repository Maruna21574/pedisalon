<?php
$active = 'onas';
$title = 'O nás';
$desc = 'Spoznajte PediSalón ZK – salón pedikúry a ošetrenia chodidiel. Šetrný prístup, hygiena a starostlivosť o každého klienta.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php">Domov</a> › <span>O nás</span></nav>
    <h1 class="reveal">O nás</h1>
    <p class="reveal" style="--d:.1s">Za každým pekným krokom stojí zdravé a upravené chodidlo.</p>
  </div>
</section>

<section class="section">
  <div class="wrap split rev">
    <div class="split-media reveal left">
      <div class="img-main"><img src="assets/img/logo.jpg" alt="Logo <?= e($S['name']) ?>" style="object-position:center"></div>
      <div class="img-sec"><img src="assets/img/pedikura-4.jpg" alt="Upravené nohy" loading="lazy"></div>
    </div>
    <div class="reveal right">
      <span class="kicker" style="font-family:var(--font-script);color:var(--rose-600);font-size:1.7rem">Náš príbeh</span>
      <h2>Vitajte v PediSalóne ZK</h2>
      <p>Náš salón vznikol z jednoduchého presvedčenia: <b>nohy si zaslúžia rovnakú starostlivosť ako tvár či ruky</b>. Venujeme sa nielen estetickej pedikúre, ale aj problémom, s ktorými sa ľudia často boja ísť – mozole, kurie oká, zhrubnuté nechty alebo vrastajúci necht.</p>
      <p>Logo salóna – chodidlo so srdiečkom a lístkami – vyjadruje naše hodnoty: <b>láska</b> k práci, <b>prírodná</b> šetrnosť a <b>precíznosť</b> v každom detaile.</p>
      <p>Pracujeme v pokojnom, čistom prostredí. Pri každej návšteve si vyhradíme čas, aby sme sa mohli venovať práve vám.</p>
      <a href="rezervacia.php" class="btn">Objednať sa <?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker">Naše hodnoty</span><h2>Na čom si zakladáme</h2></div>
    <div class="features">
      <div class="feature"><div class="ic"><?= icon('shield') ?></div><h3>Hygiena</h3><p>Čisté pomôcky, upratané prostredie a prísne postupy pri každom klientovi.</p></div>
      <div class="feature"><div class="ic"><?= icon('heart') ?></div><h3>Empatia</h3><p>Chápeme, že niektoré problémy sú citlivé. U nás sa nemusíte hanbiť.</p></div>
      <div class="feature"><div class="ic"><?= icon('leaf') ?></div><h3>Šetrnosť</h3><p>Jemné postupy a kvalitná kozmetika, ktoré vaše nohy nepodráždia.</p></div>
      <div class="feature"><div class="ic"><?= icon('sparkle') ?></div><h3>Precíznosť</h3><p>Ku každému ošetreniu pristupujeme s pozornosťou k detailom.</p></div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
