<?php
$active = 'sluzby';
$title = 'Služby';
$desc = 'Prehľad služieb PediSalónu ZK: pedikúra, gél lak, brúsenie päty, ošetrenie mykózy, bradavíc a kurích očí, špona na vrastajúci necht.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span><?= t('nav.services') ?></span></nav>
    <h1 class="reveal"><?= t('sluzby.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('sluzby.hero.lead') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="cards">
      <?php foreach ($C['services'] as $id => $s): ?>
      <a class="card" href="<?= e($s['page']) ?>">
        <div class="thumb"><img src="<?= e(img_thumb($s['img'])) ?>" alt="<?= e($s['name']) ?>" loading="lazy" decoding="async"><div class="badge-ico"><?= icon($s['icon']) ?></div></div>
        <div class="body">
          <h3><?= e($s['name']) ?></h3>
          <p><?= e($s['short']) ?></p>
          <div class="meta"><span class="price"><?= t('common.from') ?> <?= money($s['price']) ?> · <?= (int)$s['duration'] ?> <?= t('common.min') ?></span><span class="more"><?= t('sluzby.card.more') ?> <?= icon('arrow') ?></span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker"><?= t('sluzby.help.kicker') ?></span><h2><?= t('sluzby.help.title') ?></h2>
      <p><?= t('sluzby.help.text') ?></p>
      <a href="rezervacia.php" class="btn"><?= t('nav.book') ?> <?= icon('arrow') ?></a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
