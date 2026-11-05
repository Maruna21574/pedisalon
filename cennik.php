<?php
$active = 'cennik';
$title = 'Cenník';
$desc = 'Cenník služieb PediSalónu ZK – pedikúra, gél lak, brúsenie päty, ošetrenie mykózy, bradavíc a špona.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php">Domov</a> › <span>Cenník</span></nav>
    <h1 class="reveal">Cenník</h1>
    <p class="reveal" style="--d:.1s">Transparentné ceny bez skrytých poplatkov. Konečná cena sa môže mierne líšiť podľa rozsahu ošetrenia.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="price-group reveal">
      <?php foreach ($C['services'] as $id => $s): ?>
      <div class="price-row">
        <div><h3><a href="<?= e($s['page']) ?>" style="color:inherit"><?= e($s['name']) ?></a></h3><p><?= e($s['short']) ?></p></div>
        <div class="dur"><?= icon('clock') ?> <?= (int)$s['duration'] ?> min</div>
        <div class="amt">od <?= money($s['price']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;color:var(--muted);max-width:40em;margin:0 auto 30px" class="reveal">Ceny sú orientačné. Presnú cenu vám povieme po prezretí chodidiel. Platba prebieha v salóne po ošetrení.</p>
    <p style="text-align:center" class="reveal"><a href="rezervacia.php" class="btn btn-lg">Objednať sa online <?= icon('arrow') ?></a></p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
