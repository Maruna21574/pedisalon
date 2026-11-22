<?php
$active = 'poradna';
$title = 'Poradňa – najčastejšie problémy chodidiel';
$desc = 'Poradňa PediSalónu ZK: vrastajúci necht, mykóza, kurie oká, bradavice, popraskané päty a ďalšie ťažkosti chodidiel – čo ich spôsobuje, ako sa prejavujú a čo pomáha.';
require __DIR__ . '/inc/header.php';

$topics = poradna_data();
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span><?= t('poradna.hero.title') ?></span></nav>
    <h1 class="reveal"><?= t('poradna.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('poradna.hero.lead') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="advice-intro reveal">
      <p><?= t('poradna.intro') ?></p>
      <div class="note"><strong>Dôležité:</strong> <?= t('poradna.note') ?></div>
    </div>

    <nav class="advice-nav reveal" aria-label="Rýchly výber témy">
      <?php foreach ($topics as $t): ?><a href="#<?= e($t['id']) ?>"><?= icon($t['icon']) ?> <?= e($t['h']) ?></a><?php endforeach; ?>
    </nav>

    <div class="advice-list">
      <?php foreach ($topics as $t): ?>
      <article class="advice reveal" id="<?= e($t['id']) ?>">
        <header>
          <span class="advice-ico"><?= icon($t['icon']) ?></span>
          <div>
            <h2><?= e($t['h']) ?></h2>
            <p><?= e($t['intro']) ?></p>
          </div>
        </header>
        <div class="advice-cols">
          <div>
            <h3><?= t('poradna.h.causes') ?></h3>
            <ul class="ticks"><?php foreach ($t['causes'] as $c): ?><li><?= icon('check') ?> <?= e($c) ?></li><?php endforeach; ?></ul>
          </div>
          <div>
            <h3><?= t('poradna.h.signs') ?></h3>
            <ul class="ticks"><?php foreach ($t['signs'] as $c): ?><li><?= icon('check') ?> <?= e($c) ?></li><?php endforeach; ?></ul>
            <h3><?= t('poradna.h.help') ?></h3>
            <p><?= e($t['help']) ?></p>
          </div>
        </div>
        <div class="note"><?= e($t['doctor']) ?></div>
        <a href="<?= e($t['link'][0]) ?>" class="btn btn-ghost btn-sm"><?= e($t['link'][1]) ?> <?= icon('arrow') ?></a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker"><?= t('poradna.prev.kicker') ?></span><h2><?= t('poradna.prev.title') ?></h2></div>
    <div class="features">
      <?php foreach (array_slice(tp('poradna.prev.items'), 0, 4) as $i => [$h, $p]): ?>
      <div class="feature"><div class="ic"><?= icon(['shield', 'heart', 'nail', 'sparkle'][$i % 4]) ?></div><h3><?= e($h) ?></h3><p><?= e($p) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker"><?= t('poradna.doc.kicker') ?></span><h2><?= t('poradna.doc.title') ?></h2></div>
    <div class="faq reveal">
      <?php foreach (tp('poradna.doc.items') as $i => [$q, $a]): ?>
      <details <?= $i === 0 ? 'open' : '' ?>><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:44px" class="reveal"><a href="rezervacia.php" class="btn btn-lg"><?= t('nav.book') ?> <?= icon('arrow') ?></a></p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
