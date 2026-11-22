<?php
$active = 'cennik';
$title = 'Cenník';
$desc = 'Cenník služieb PediSalónu ZK – pedikúra, lakovanie Gellak a Lak Moyra, ošetrenie nechtov, kurie oká, reflexná masáž a špona.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span><?= t('cennik.hero.title') ?></span></nav>
    <h1 class="reveal"><?= t('cennik.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('cennik.hero.lead') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <?php $cats = service_categories(); ?>
    <div class="filters reveal" id="catFilter" role="group" aria-label="Filter kategórií">
      <button type="button" class="on" data-cat="*">Všetko</button>
      <?php foreach (array_keys($cats) as $cat): ?><button type="button" data-cat="<?= e(cat_slug($cat)) ?>"><?= e($cat) ?></button><?php endforeach; ?>
    </div>
    <?php foreach ($cats as $cat => $items): ?>
    <div class="cat-block" data-cat="<?= e(cat_slug($cat)) ?>">
    <h2 class="cat-title reveal"><?= e($cat) ?></h2>
    <div class="price-group reveal">
      <?php foreach ($items as $id => $s): ?>
      <div class="price-row">
        <div><h3><a href="<?= e($s['page']) ?>" style="color:inherit"><?= e($s['name']) ?></a></h3><p><?= e($s['short']) ?></p></div>
        <div class="dur"><?= icon('clock') ?> <?= (int)$s['duration'] ?> <?= t('common.min') ?></div>
        <div class="amt"><?= money($s['price']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    </div>
    <?php endforeach; ?>
    <p style="text-align:center;color:var(--muted);max-width:40em;margin:0 auto 30px" class="reveal"><?= t('cennik.note') ?></p>
    <p style="text-align:center" class="reveal"><a href="rezervacia.php" class="btn btn-lg"><?= t('cennik.btn') ?> <?= icon('arrow') ?></a></p>
  </div>
</section>
<script>
(function () {
  var bar = document.getElementById('catFilter'); if (!bar) return;
  var btns = bar.querySelectorAll('button'), blocks = document.querySelectorAll('.cat-block');
  function show(c) {
    btns.forEach(function (b) { b.classList.toggle('on', b.dataset.cat === c); });
    blocks.forEach(function (bl) { bl.hidden = !(c === '*' || bl.dataset.cat === c); });
    history.replaceState(null, '', c === '*' ? location.pathname : '#' + c);
  }
  btns.forEach(function (b) { b.addEventListener('click', function () { show(b.dataset.cat); }); });
  var h = location.hash.slice(1); if (h && bar.querySelector('[data-cat="' + h + '"]')) show(h);
})();
</script>
<?php require __DIR__ . '/inc/footer.php'; ?>
