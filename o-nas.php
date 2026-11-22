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
      <div class="img-main" style="aspect-ratio:4/3;transform:none"><img src="<?= e(timg('about.hyg.img')) ?>" alt="<?= e(tx('about.hyg.title')) ?>" loading="lazy" decoding="async"></div>
    </div>
    <div class="reveal right">
      <span class="kicker"><?= t('about.hyg.kicker') ?></span>
      <h2><?= t('about.hyg.title') ?></h2>
      <p><?= t('about.hyg.text') ?></p>
      <ul class="ticks">
        <?php foreach (tl('about.hyg.ticks') as $tick): ?><li><?= icon('check') ?> <?= e($tick) ?></li><?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
<?php $certs = certificates_data(); ?>
<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?= t('about.cert.kicker') ?></span>
      <h2><?= t('about.cert.title') ?></h2>
      <p><?= t('about.cert.text') ?></p>
    </div>
    <div class="certs">
      <?php foreach ($certs as $cert): $file = $cert['file']; $name = $cert['title']; $date = $cert['date']; $fp = __DIR__ . '/assets/img/' . $file; $ver = is_file($fp) ? filemtime($fp) : 0; if (!$ver) continue; ?>
      <figure class="cert reveal">
        <a href="<?= e(img_url($file)) ?>?v=<?= $ver ?>" data-lightbox data-label="<?= e($name) ?>" class="cert-paper">
          <img src="<?= e(img_url($file)) ?>?v=<?= $ver ?>" alt="Certifikát: <?= e($name) ?>" loading="lazy" decoding="async">
        </a>
        <figcaption><b><?= e($name) ?></b><span><?= e($date) ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
