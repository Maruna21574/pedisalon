<?php
$active = 'galeria';
$title = 'Galéria';
$desc = 'Fotogaléria prác PediSalónu ZK – pedikúra, brúsenie päty, mykóza, bradavice, špona.';
require __DIR__ . '/inc/header.php';

$G = gallery_data();
$items = $G['items'];
// Filtre len pre kategórie, v ktorých sú fotky
$usedCats = array_filter($G['cats'], fn($slug) => in_array($slug, array_column($items, 'cat'), true), ARRAY_FILTER_USE_KEY);
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span><?= t('galeria.hero.title') ?></span></nav>
    <h1 class="reveal"><?= t('galeria.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('galeria.hero.lead') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="filters reveal">
      <button class="on" data-filter="all"><?= t('galeria.all') ?></button>
      <?php foreach ($usedCats as $k => $label): ?><button data-filter="<?= e($k) ?>"><?= e($label) ?></button><?php endforeach; ?>
    </div>
    <div class="masonry">
      <?php foreach ($items as $it): ?>
      <a href="<?= e(img_url($it['file'])) ?>" data-lightbox data-cat="<?= e($it['cat']) ?>" data-label="<?= e($it['label']) ?>"><img src="<?= e(img_thumb($it['file'])) ?>" alt="<?= e($it['label']) ?>" loading="lazy" decoding="async"></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
