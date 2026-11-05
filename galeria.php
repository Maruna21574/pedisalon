<?php
$active = 'galeria';
$title = 'Galéria';
$desc = 'Fotogaléria prác PediSalónu ZK – pedikúra, brúsenie päty, mykóza, bradavice, špona.';
require __DIR__ . '/inc/header.php';

$cats = ['pedikura' => ['Pedikúra', 10], 'brusenie' => ['Brúsenie päty', 4], 'mykoza' => ['Mykóza', 2], 'bradavice' => ['Bradavice a kurie oká', 4], 'spona' => ['Špona', 6]];
// Poradie premiešané tak, aby galéria vyzerala pestro
$items = [];
$max = max(array_column($cats, 1));
for ($i = 1; $i <= $max; $i++) foreach ($cats as $k => [$label, $n]) if ($i <= $n) $items[] = [$k, $i, $label];
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php">Domov</a> › <span>Galéria</span></nav>
    <h1 class="reveal">Galéria</h1>
    <p class="reveal" style="--d:.1s">Pozrite sa na výsledky našej práce. Kliknutím fotku zväčšíte.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="filters reveal">
      <button class="on" data-filter="all">Všetko</button>
      <?php foreach ($cats as $k => [$label]): ?><button data-filter="<?= $k ?>"><?= e($label) ?></button><?php endforeach; ?>
    </div>
    <div class="masonry">
      <?php foreach ($items as [$k, $i, $label]): ?>
      <a href="assets/img/<?= $k ?>-<?= $i ?>.jpg" data-lightbox data-cat="<?= $k ?>" data-label="<?= e($label) ?>"><img src="assets/img/<?= $k ?>-<?= $i ?>.jpg" alt="<?= e($label) ?> – fotka <?= $i ?>" loading="lazy"></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
