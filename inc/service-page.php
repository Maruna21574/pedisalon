<?php
/** Šablóna podstránky služby. Očakáva premennú $pageKey (názov súboru). */
require_once __DIR__ . '/lib.php';
$P = page_content($pageKey);   // predvolený obsah + úpravy z administrácie
$active = $P['active'];
$title = $P['title'];
$desc = $P['desc'];
require __DIR__ . '/header.php';
// Služby, ktoré patria k tejto podstránke (zmazané služby sa preskočia)
$svcs = [];
foreach ($P['services'] as $id) if (isset($C['services'][$id])) $svcs[] = [$id, $C['services'][$id]];
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs" aria-label="Omrvinková navigácia"><a href="index.php"><?= t('nav.home') ?></a> › <a href="sluzby.php"><?= t('nav.services') ?></a> › <span><?= e($P['title']) ?></span></nav>
    <h1 class="reveal"><?= e($P['h1']) ?></h1>
    <p class="reveal" style="--d:.1s"><?= e($P['lead']) ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap svc-layout">
    <article class="prose">
      <?php if ($P['intro']): ?>
      <h2 class="reveal"><?= t('svcpage.h.about') ?></h2>
      <?php foreach ($P['intro'] as $p): ?><p class="reveal"><?= e($p) ?></p><?php endforeach; ?>
      <?php endif; ?>

      <?php if (!empty($P['note'])): ?><div class="note reveal"><?= e($P['note']) ?></div><?php endif; ?>

      <?php if ($P['includes']): ?>
      <h2 class="reveal"><?= t('svcpage.h.includes') ?></h2>
      <ul class="ticks reveal">
        <?php foreach ($P['includes'] as $i): ?><li><?= icon('check') ?> <?= e($i) ?></li><?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <?php if ($P['photos']): ?>
      <div class="photo-strip">
        <?php foreach ($P['photos'] as $f): ?>
        <a href="<?= e(img_url($f)) ?>" data-lightbox data-label="<?= e($P['title']) ?>"><img src="<?= e(img_thumb($f)) ?>" alt="<?= e($P['title']) ?> – ukážka" loading="lazy" decoding="async"></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($P['steps']): ?>
      <h2 class="reveal"><?= t('svcpage.h.steps') ?></h2>
      <div class="steps two" style="margin-top:36px">
        <?php foreach ($P['steps'] as [$h, $t]): ?><div class="step"><h3><?= e($h) ?></h3><p><?= e($t) ?></p></div><?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($P['tips']): ?>
      <h2 class="reveal"><?= t('svcpage.h.tips') ?></h2>
      <ul class="ticks reveal">
        <?php foreach ($P['tips'] as $i): ?><li><?= icon('heart') ?> <?= e($i) ?></li><?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <?php if ($P['faq']): ?>
      <h2 class="reveal"><?= t('svcpage.h.faq') ?></h2>
      <div class="faq" style="margin:0">
        <?php foreach ($P['faq'] as [$q, $a]): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </article>

    <aside class="aside-card reveal right">
      <?php foreach ($svcs as $k => [$id, $s]): ?>
        <?php if ($k): ?><hr style="border:0;border-top:1px dashed var(--sage-200);margin:24px 0"><?php endif; ?>
        <?php if (count($svcs) > 1): ?><b style="color:var(--sage-900)"><?= e($s['name']) ?></b><?php endif; ?>
        <div class="price"><small><?= t('common.from') ?></small> <?= money($s['price']) ?></div>
        <ul>
          <li><?= icon('clock') ?> <?= e(str_replace('{min}', (int)$s['duration'], tx('svcpage.duration'))) ?></li>
          <li><?= icon('shield') ?> <?= t('svcpage.hygiene') ?></li>
          <?php if ($k === count($svcs) - 1): ?><li><?= icon('heart') ?> <?= t('svcpage.care') ?></li><?php endif; ?>
        </ul>
        <a href="rezervacia.php?service=<?= e($id) ?>" class="btn"><?= t('svcpage.btn') ?> <?= icon('arrow') ?></a>
      <?php endforeach; ?>
      <p style="margin:18px 0 0;font-size:.9rem;color:var(--muted)"><?= t('svcpage.call') ?> <a href="tel:<?= e($S['phone_raw']) ?>"><b><?= e($S['phone']) ?></b></a></p>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
