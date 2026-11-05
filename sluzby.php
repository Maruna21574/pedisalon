<?php
$active = 'sluzby';
$title = 'Služby';
$desc = 'Prehľad služieb PediSalónu ZK: pedikúra, gél lak, brúsenie päty, ošetrenie mykózy, bradavíc a kurích očí, špona na vrastajúci necht.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php">Domov</a> › <span>Služby</span></nav>
    <h1 class="reveal">Naše služby</h1>
    <p class="reveal" style="--d:.1s">Od estetickej pedikúry po ošetrenie problémových chodidiel – všetko na jednom mieste.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="cards">
      <?php foreach ($C['services'] as $id => $s): ?>
      <a class="card" href="<?= e($s['page']) ?>">
        <div class="thumb"><img src="assets/img/<?= e($s['img']) ?>" alt="<?= e($s['name']) ?>" loading="lazy"><div class="badge-ico"><?= icon($s['icon']) ?></div></div>
        <div class="body">
          <h3><?= e($s['name']) ?></h3>
          <p><?= e($s['short']) ?></p>
          <div class="meta"><span class="price">od <?= money($s['price']) ?> · <?= (int)$s['duration'] ?> min</span><span class="more">Detail <?= icon('arrow') ?></span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker">Nie ste si istí?</span><h2>Poradíme vám</h2>
      <p>Ak neviete, ktorá služba je pre vás tá pravá, vyberte pri rezervácii kompletnú pedikúru a v poznámke nám napíšte, čo vás trápi. Postup upravíme na mieru.</p>
      <a href="rezervacia.php" class="btn">Rezervovať termín <?= icon('arrow') ?></a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
