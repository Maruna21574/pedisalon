<?php
$active = 'onas';
$title = 'Prevádzka';
$desc = 'Pozrite si prevádzku salónu – čisté, pokojné a príjemné prostredie, v ktorom sa o vaše nohy postaráme.';
require __DIR__ . '/inc/header.php';
$photos = tphotos('premises.photos');
$label = tx('premises.hero.title');
/** Fotka (náhľad + plná veľkosť v lightboxe) alebo zástupný rámik. */
$ph = fn(int $i) => isset($photos[$i])
    ? '<a href="' . e(img_url($photos[$i])) . '" data-lightbox data-label="' . e($label) . '"><img src="' . e(img_thumb($photos[$i])) . '" alt="' . e($label) . '" loading="lazy" decoding="async"></a>'
    : '';
$rest = array_slice($photos, 2);
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <a href="o-nas.php"><?= t('nav.about') ?></a> › <span><?= t('nav.premises') ?></span></nav>
    <h1 class="reveal"><?= t('premises.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('premises.hero.lead') ?></p>
  </div>
</section>

<!-- Text vedľa fotiek -->
<section class="section">
  <div class="wrap split">
    <div class="reveal left">
      <span class="kicker"><?= t('premises.kicker') ?></span>
      <h2><?= t('premises.title') ?></h2>
      <?= trich('premises.body') ?>
      <a href="rezervacia.php" class="btn"><?= t('nav.book') ?> <?= icon('arrow') ?></a>
    </div>
    <div class="split-media reveal right">
      <div class="img-main premises-main">
        <?php if (isset($photos[0])): ?><?= $ph(0) ?><?php else: ?><div class="photo-ph"><span><?= icon('foot') ?>Fotka prevádzky</span></div><?php endif; ?>
      </div>
      <div class="img-sec premises-sec">
        <?php if (isset($photos[1])): ?><?= $ph(1) ?><?php else: ?><div class="photo-ph"><span><?= icon('foot') ?></span></div><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Ďalšie fotky -->
<?php if ($rest || !$photos): ?>
<section class="section tint">
  <div class="wrap">
    <div class="ph-grid">
      <?php if ($rest): foreach ($rest as $i => $f): ?>
        <?= $ph($i + 2) ?>
      <?php endforeach; else: for ($i = 0; $i < 3; $i++): ?>
        <div class="photo-ph"><span><?= icon('foot') ?>Fotka prevádzky</span></div>
      <?php endfor; endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Výhody -->
<section class="section">
  <div class="wrap">
    <div class="sec-head reveal"><h2><?= t('premises.features.title') ?></h2></div>
    <div class="features">
      <?php foreach (tp('premises.features') as $i => [$h, $p]): ?>
      <div class="feature"><div class="ic"><?= icon(['shield', 'leaf', 'heart', 'sparkle'][$i % 4]) ?></div><h3><?= e($h) ?></h3><p><?= e($p) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Kde nás nájdete: text vedľa údajov -->
<section class="section tint">
  <div class="wrap split">
    <div class="reveal right">
      <h2><?= t('premises.visit.title') ?></h2>
      <p><?= t('premises.visit.text') ?></p>
      <a href="kontakt.php" class="btn"><?= t('premises.visit.btn') ?> <?= icon('arrow') ?></a>
    </div>
    <div class="reveal left">
      <ul class="info-list visit-card">
        <li><span class="ic"><?= icon('pin') ?></span><div><b><?= t('kontakt.l.address') ?></b><?= e($S['address']) ?><?php if (!empty($S['location_note'])): ?><br><span style="color:var(--muted)"><?= e($S['location_note']) ?></span><?php endif; ?></div></li>
        <li><span class="ic"><?= icon('phone') ?></span><div><b><?= t('kontakt.l.phone') ?></b><a href="tel:<?= e($S['phone_raw']) ?>"><?= e($S['phone']) ?></a></div></li>
        <li><span class="ic"><?= icon('clock') ?></span><div><b><?= t('kontakt.l.hours') ?></b><?= hours_summary() ?></div></li>
      </ul>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
