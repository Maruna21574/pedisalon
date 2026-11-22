<?php
$active = 'onas';
$title = 'O nás';
$desc = 'Spoznajte PediSalón ZK – salón pedikúry a ošetrenia chodidiel. Šetrný prístup, hygiena a starostlivosť o každého klienta.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span><?= t('about.hero.title') ?></span></nav>
    <h1 class="reveal"><?= t('about.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('about.hero.lead') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap split rev">
    <div class="split-media reveal left">
      <?php if (tx('about.img') !== ''): ?>
      <div class="img-main"><img src="<?= e(timg('about.img')) ?>" alt="<?= e($S['name']) ?>" loading="lazy" decoding="async"></div>
      <?php else: ?>
      <div class="img-main photo-ph"><span><?= icon('foot') ?>Vaša fotka</span></div>
      <?php endif; ?>
    </div>
    <div class="reveal right">
      <span class="kicker"><?= t('about.kicker') ?></span>
      <h2><?= t('about.title') ?></h2>
      <?= trich('about.body') ?>
      <a href="rezervacia.php" class="btn"><?= t('about.btn') ?> <?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker"><?= t('about.values.kicker') ?></span><h2><?= t('about.values.title') ?></h2></div>
    <div class="features">
      <?php foreach (tp('about.values') as $i => [$h, $p]): ?>
      <div class="feature"><div class="ic"><?= icon(['shield', 'heart', 'leaf', 'sparkle'][$i % 4]) ?></div><h3><?= e($h) ?></h3><p><?= e($p) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap split">
    <div class="split-media reveal left">
      <div class="img-main" style="aspect-ratio:4/3;transform:none"><img src="assets/img/salon-sterilizacia.jpg" alt="Pracovisko s pomôckami na dezinfekciu" loading="lazy" decoding="async"></div>
    </div>
    <div class="reveal right">
      <span class="kicker">Hygiena a bezpečnosť</span>
      <h2>Čisté pracovisko a odborný postup</h2>
      <p>Každé ošetrenie začíname dezinfekciou. Pomôcky po každom klientovi čistíme a dezinfikujeme a pracovisko máme upratané a pripravené pre ďalšieho klienta.</p>
      <ul class="ticks">
        <li><?= icon('check') ?> Dezinfekcia pri každom ošetrení</li>
        <li><?= icon('check') ?> Čisté a dezinfikované pomôcky pre každého klienta</li>
        <li><?= icon('check') ?> Odborné vzdelanie a pravidelné školenia – certifikáty nájdete nižšie</li>
      </ul>
    </div>
  </div>
</section>
<?php
$certs = [
    ['cert-podofix-spony.jpg',         'Špony Podofix – ošetrenie zarastených nechtov',     '5. 9. 2025'],
    ['cert-zhrubnute-nechty.jpg',      'Ošetrenie zhrubnutých nechtov',                     '5. 9. 2025'],
    ['cert-estetika-pedikura.jpg',     'Dokonalá estetická pedikúra – aplikácia géllaku',   '23. 4. 2025'],
    ['cert-pristrojova-pedikura.jpg',  'Prístrojová pedikúra – brúska a frézy (8 hodín)',   '15. 4. 2025'],
    ['cert-frezky.jpg',                'Frézky v pedikérskej praxi (online školenie)',      '28. 12. 2025'],
    ['cert-reflexna-masaz.jpg',        'Reflexná masáž chodidiel',                          '18. 2. 2026'],
    ['cert-pedikersky-workshop.jpg',   'Pedikérsky workshop, Liptovský Mikuláš',            '27. 9. 2026'],
    ['cert-podopharm-seminar.jpg',     'Veľký Podopharm seminár, Nitra',                    '15. 5. 2026'],
    ['cert-pharm-foot.jpg',            'Pharm Foot – produkty v terapii vírusových bradavíc (webinár)', '24. 9. 2025'],
    ['cert-titanova-nit.jpg',          'Titánová niť NITI – školenie',                      '17. 11. 2026'],
    ['cert-mykoticke-ochorenia.jpg',   'Mykotické ochorenia na nechtoch a koži',            '4. 11. 2026'],
];
?>
<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker">Odbornosť</span>
      <h2>Certifikáty a odborné školenia</h2>
      <p>O vaše nohy sa staráme s odbornými znalosťami. Sústavne sa vzdelávame a absolvované kurzy a školenia nájdete nižšie. Kliknutím si certifikát zväčšíte.</p>
    </div>
    <div class="certs">
      <?php foreach ($certs as [$file, $name, $date]): ?>
      <figure class="cert reveal">
        <a href="assets/img/certifikaty/<?= e($file) ?>?v=<?= filemtime(__DIR__ . "/assets/img/certifikaty/" . $file) ?>" data-lightbox data-label="<?= e($name) ?>" class="cert-paper">
          <img src="assets/img/certifikaty/<?= e($file) ?>?v=<?= filemtime(__DIR__ . "/assets/img/certifikaty/" . $file) ?>" alt="Certifikát: <?= e($name) ?>" loading="lazy" decoding="async">
        </a>
        <figcaption><b><?= e($name) ?></b><span><?= e($date) ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
