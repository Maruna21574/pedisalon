<?php
require_once __DIR__ . '/inc/lib.php';
$active = '';
$title = 'Ochrana osobných údajov';
$desc = 'Zásady spracovania osobných údajov ' . cfg()['salon']['name'] . '.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span>Ochrana osobných údajov</span></nav>
    <h1>Ochrana osobných údajov</h1>
  </div>
</section>
<section class="section">
  <div class="wrap prose" style="max-width:820px">
    <?= trich('privacy.body') ?>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
