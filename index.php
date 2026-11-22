<?php
$active = 'home';
$title = null;
require __DIR__ . '/inc/header.php';
?>
<section class="hero">
  <span class="blob b1"></span><span class="blob b2"></span><span class="blob b3"></span>
  <div class="wrap hero-grid">
    <div class="hero-text">
      <span class="eyebrow reveal"><?= icon('leaf') ?> <?= t('home.eyebrow') ?></span>
      <h1 class="reveal" style="--d:.1s"><?= t('home.h1') ?><span class="script"><?= t('home.h1script') ?></span></h1>
      <p class="lead reveal" style="--d:.2s"><?= t('home.lead') ?></p>
      <div class="hero-actions reveal" style="--d:.3s">
        <a href="rezervacia.php" class="btn btn-lg"><?= t('nav.book') ?> <?= icon('arrow') ?></a>
        <a href="sluzby.php" class="btn btn-ghost btn-lg"><?= t('home.btn.services') ?></a>
      </div>
    </div>
    <div class="hero-visual reveal zoom" style="--d:.2s" id="heroVisual">
      <div class="stage">
        <div class="oval-ring" style="--z:0px"></div>
        <div class="oval" style="--z:40px"><img src="<?= e(timg('home.hero.img')) ?>" alt="Upravené nohy po pedikúre"></div>
        <div class="medal" style="--z:110px">
          <svg viewBox="0 0 200 200" class="ring-text" aria-hidden="true"><defs><path id="circ" d="M100,100 m-82,0 a82,82 0 1,1 164,0 a82,82 0 1,1 -164,0"/></defs><text><textPath href="#circ"><?= e($S['name']) ?> ✿ pedikúra ✿ starostlivosť ✿ </textPath></text></svg>
          <img src="assets/img/logo.png" alt="Logo <?= e($S['name']) ?>">
        </div>
        <div class="chip c1" style="--z:130px"><?= icon('clock') ?> <?= t('home.chip1') ?></div>
      </div>
    </div>
  </div>
</section>

<div class="service-strip">
  <div class="wrap">
    <?php foreach (tl('home.marquee') as $m): ?><span><?= e($m) ?></span><?php endforeach; ?>
  </div>
</div>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?= t('home.services.kicker') ?></span>
      <h2><?= t('home.services.title') ?></h2>
      <p><?= t('home.services.text') ?></p>
    </div>
    <div class="cards">
      <?php foreach (service_groups() as $s): ?>
      <a class="card" href="<?= e($s['page']) ?>">
        <div class="thumb"><img src="<?= e(img_thumb($s['img'])) ?>" alt="<?= e($s['name']) ?>" loading="lazy" decoding="async"><div class="badge-ico"><?= icon($s['icon']) ?></div></div>
        <div class="body">
          <h3><?= e($s['name']) ?></h3>
          <p><?= e($s['short']) ?></p>
          <div class="meta"><span class="price"><?= $s['count'] > 1 ? t('common.from') . ' ' : '' ?><?= money($s['min']) ?></span><span class="more"><?= t('home.card.more') ?> <?= icon('arrow') ?></span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:44px" class="reveal"><a href="cennik.php" class="btn btn-ghost"><?= t('home.prices.btn') ?></a></p>
  </div>
</section>

<section class="section tint">
  <div class="wrap split">
    <div class="split-media reveal left">
      <div class="img-main" data-parallax="-0.02"><img src="<?= e(timg('home.about.img1')) ?>" alt="Upravené nohy po pedikúre" loading="lazy" decoding="async"></div>
      <div class="img-sec"><img src="<?= e(timg('home.about.img2')) ?>" alt="Pedikúra" loading="lazy" decoding="async"></div>
    </div>
    <div class="reveal right">
      <span class="kicker"><?= t('home.about.kicker') ?></span>
      <h2><?= t('home.about.title') ?></h2>
      <p><?= t('home.about.text') ?></p>
      <ul class="ticks">
        <?php foreach (tl('home.about.ticks') as $tick): ?><li><?= icon('check') ?> <?= e($tick) ?></li><?php endforeach; ?>
      </ul>
      <a href="o-nas.php" class="btn"><?= t('home.about.btn') ?> <?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?= t('home.flow.kicker') ?></span>
      <h2><?= t('home.flow.title') ?></h2>
    </div>
    <ol class="flow" id="flow">
      <?php foreach (tp('home.flow.steps') as $i => [$h, $p]): ?>
      <li><span class="num"><?= sprintf('%02d', $i + 1) ?></span><span class="dot"><?= icon(['clock', 'heart', 'sparkle', 'leaf'][$i % 4]) ?></span><h3><?= e($h) ?></h3><p><?= e($p) ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?= t('home.work.kicker') ?></span>
      <h2><?= t('home.work.title') ?></h2>
    </div>
  </div>
  <div class="slider reveal" id="workSlider">
    <div class="track" tabindex="0" aria-label="Ukážky prác – posúvajte do strán">
      <?php foreach (array_filter(gallery_data()['items'], fn($it) => !empty($it['home'])) as $it): ?>
      <a href="<?= e(img_url($it['file'])) ?>" data-lightbox data-label="<?= e($it['label']) ?>" draggable="false"><img src="<?= e(img_thumb($it['file'])) ?>" alt="<?= e($it['label']) ?>" loading="lazy" decoding="async" draggable="false"><span><?= e($it['label']) ?></span></a>
      <?php endforeach; ?>
    </div>
    <div class="wrap slider-ui">
      <div class="bar-progress"><i></i></div>
      <div class="arrows"><button type="button" class="s-prev" aria-label="Späť">‹</button><button type="button" class="s-next" aria-label="Ďalej">›</button></div>
    </div>
  </div>
  <div class="wrap"><p style="text-align:center;margin-top:34px" class="reveal"><a href="galeria.php" class="btn btn-ghost"><?= t('home.work.btn') ?></a></p></div>
</section>

<?php require __DIR__ . '/inc/reviews.php'; ?>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker"><?= t('home.faq.kicker') ?></span><h2><?= t('home.faq.title') ?></h2></div>
    <div class="faq reveal">
      <?php foreach (tp('faq.general') as [$q, $a]): ?>
      <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
