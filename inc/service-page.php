<?php
/** Šablóna podstránky služby. Očakáva premennú $pageKey (názov súboru). */
require_once __DIR__ . '/lib.php';
$all = require __DIR__ . '/service-content.php';
$P = $all[$pageKey];
$active = $P['active'];
$title = $P['title'];
$desc = $P['desc'];
require __DIR__ . '/header.php';
$svcs = array_map(fn($id) => [$id, $C['services'][$id]], $P['services']);
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs" aria-label="Omrvinková navigácia"><a href="index.php">Domov</a> › <a href="sluzby.php">Služby</a> › <span><?= e($P['title']) ?></span></nav>
    <h1 class="reveal"><?= e($P['h1']) ?></h1>
    <p class="reveal" style="--d:.1s"><?= e($P['lead']) ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap svc-layout">
    <article class="prose">
      <h2 class="reveal">O službe</h2>
      <?php foreach ($P['intro'] as $p): ?><p class="reveal"><?= e($p) ?></p><?php endforeach; ?>

      <?php if (!empty($P['note'])): ?><div class="note reveal"><?= e($P['note']) ?></div><?php endif; ?>

      <h2 class="reveal">Čo zahŕňa</h2>
      <ul class="ticks reveal">
        <?php foreach ($P['includes'] as $i): ?><li><?= icon('check') ?> <?= e($i) ?></li><?php endforeach; ?>
      </ul>

      <div class="photo-strip">
        <?php foreach ($P['photos'] as $f): ?>
        <a href="assets/img/<?= e($f) ?>.jpg" data-lightbox data-label="<?= e($P['title']) ?>"><img src="assets/img/<?= e($f) ?>.jpg" alt="<?= e($P['title']) ?> – ukážka" loading="lazy"></a>
        <?php endforeach; ?>
      </div>

      <h2 class="reveal">Ako to prebieha</h2>
      <div class="steps two" style="margin-top:36px">
        <?php foreach ($P['steps'] as [$h, $t]): ?><div class="step"><h3><?= e($h) ?></h3><p><?= e($t) ?></p></div><?php endforeach; ?>
      </div>

      <h2 class="reveal">Tipy pre vaše nohy</h2>
      <ul class="ticks reveal">
        <?php foreach ($P['tips'] as $i): ?><li><?= icon('heart') ?> <?= e($i) ?></li><?php endforeach; ?>
      </ul>

      <h2 class="reveal">Časté otázky</h2>
      <div class="faq" style="margin:0">
        <?php foreach ($P['faq'] as [$q, $a]): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
        <?php endforeach; ?>
      </div>
    </article>

    <aside class="aside-card reveal right">
      <?php foreach ($svcs as $k => [$id, $s]): ?>
        <?php if ($k): ?><hr style="border:0;border-top:1px dashed var(--sage-200);margin:24px 0"><?php endif; ?>
        <?php if (count($svcs) > 1): ?><b style="color:var(--sage-900)"><?= e($s['name']) ?></b><?php endif; ?>
        <div class="price"><small>od</small> <?= money($s['price']) ?></div>
        <ul>
          <li><?= icon('clock') ?> Trvanie cca <?= (int)$s['duration'] ?> minút</li>
          <li><?= icon('shield') ?> Hygienické pomôcky</li>
          <?php if ($k === count($svcs) - 1): ?><li><?= icon('heart') ?> Šetrný prístup</li><?php endif; ?>
        </ul>
        <a href="rezervacia.php?service=<?= e($id) ?>" class="btn">Rezervovať <?= icon('arrow') ?></a>
      <?php endforeach; ?>
      <p style="margin:18px 0 0;font-size:.9rem;color:var(--muted)">Alebo zavolajte: <a href="tel:<?= e($S['phone_raw']) ?>"><b><?= e($S['phone']) ?></b></a></p>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
