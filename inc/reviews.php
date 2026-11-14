<?php
/** Sekcia recenzií z Google (slider). Zobrazí sa len ak je zapnutá v administrácii a má aspoň jednu recenziu. */
$R = reviews_data();
if (empty($R['enabled']) || empty($R['items'])) return;

$googleG = '<svg class="g-logo" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>';
$wordmark = '<span class="g-word" aria-label="Google"><i style="color:#4285F4">G</i><i style="color:#EA4335">o</i><i style="color:#FBBC05">o</i><i style="color:#4285F4">g</i><i style="color:#34A853">l</i><i style="color:#EA4335">e</i></span>';
$stars = fn(int $n) => '<span class="stars" role="img" aria-label="' . $n . ' z 5 hviezdičiek">' . str_repeat('★', max(0, min(5, $n))) . '<span class="off">' . str_repeat('★', 5 - max(0, min(5, $n))) . '</span></span>';
?>
<section class="section" id="recenzie">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?= t('home.reviews.kicker') ?></span>
      <h2><?= t('home.reviews.title') ?></h2>
      <?php if ($R['rating'] !== ''): ?>
      <p class="g-summary"><?= $googleG ?> <?= $wordmark ?> <b class="g-score"><?= e($R['rating']) ?></b> <?= $stars((int)round((float)str_replace(',', '.', $R['rating']))) ?><?= $R['count'] !== '' ? ' <span>· ' . e($R['count']) . ' recenzií</span>' : '' ?></p>
      <?php endif; ?>
    </div>
  </div>
  <div class="rev-slider reveal" id="revSlider">
    <div class="rev-track" tabindex="0" aria-label="Recenzie klientov – posúvajte do strán">
      <?php foreach ($R['items'] as $it): ?>
      <figure class="rev-card">
        <div class="rev-top">
          <span class="rev-avatar" aria-hidden="true"><?= e(mb_strtoupper(mb_substr(trim($it['name']), 0, 1))) ?></span>
          <div><b><?= e($it['name']) ?></b><small><?= e($it['when']) ?></small></div>
          <?= $googleG ?>
        </div>
        <?= $stars((int)$it['stars']) ?>
        <blockquote><?= e($it['text']) ?></blockquote>
      </figure>
      <?php endforeach; ?>
    </div>
    <div class="wrap rev-ui">
      <div class="arrows"><button type="button" class="r-prev" aria-label="Späť">‹</button><button type="button" class="r-next" aria-label="Ďalej">›</button></div>
      <?php if ($R['url'] !== ''): ?>
      <div class="rev-links">
        <a href="<?= e($R['url']) ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm"><?= t('home.reviews.cta') ?></a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<script>
(function () {
  var s = document.getElementById('revSlider'); if (!s) return;
  var tr = s.querySelector('.rev-track');
  function step() { var c = tr.querySelector('.rev-card'); return c ? c.offsetWidth + 22 : 300; }
  s.querySelector('.r-prev').addEventListener('click', function () { tr.scrollBy({ left: -step(), behavior: 'smooth' }); });
  s.querySelector('.r-next').addEventListener('click', function () { tr.scrollBy({ left: step(), behavior: 'smooth' }); });
})();
</script>
