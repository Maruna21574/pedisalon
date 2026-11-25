<?php
$active = 'kontakt';
$title = 'Kontakt';
$desc = 'Kontakt a otváracie hodiny PediSalónu ZK. Zavolajte, napíšte alebo sa objednajte online.';
require __DIR__ . '/inc/header.php';
$today = (int)date('N');
?>
<section class="page-hero">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span><?= t('kontakt.hero.title') ?></span></nav>
    <h1 class="reveal"><?= t('kontakt.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('kontakt.hero.lead') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap contact-grid">
    <div>
      <ul class="info-list">
        <li><span class="ic"><?= icon('phone') ?></span><div><b><?= t('kontakt.l.phone') ?></b><a href="tel:<?= e($S['phone_raw']) ?>"><?= e($S['phone']) ?></a></div></li>
        <li><span class="ic"><?= icon('mail') ?></span><div><b><?= t('kontakt.l.email') ?></b><a href="mailto:<?= e($S['email']) ?>"><?= e($S['email']) ?></a></div></li>
        <li><span class="ic"><?= icon('pin') ?></span><div><b><?= t('kontakt.l.address') ?></b><?= e($S['address']) ?><?php if (!empty($S['location_note'])): ?><br><span style="color:var(--muted)"><?= e($S['location_note']) ?></span><?php endif; ?></div></li>
        <li><span class="ic"><?= icon('clock') ?></span><div style="flex:1"><b><?= t('kontakt.l.hours') ?></b>
          <?php if (!empty($C['hours_by_appointment'])): ?><div><?= e(tx('hours.by_appointment')) ?></div><?php else: ?>
          <table class="hours-table">
            <?php foreach (DAY_NAMES as $d => $n): $h = $C['hours'][$d]; ?>
            <tr class="<?= $d === $today ? 'today' : '' ?>"><td><?= $n ?></td><td><?= $h ? $h[0] . ' – ' . $h[1] : e(tx('kontakt.closed')) ?></td></tr>
            <?php endforeach; ?>
          </table><?php endif; ?></div></li>
      </ul>
    </div>
    <div>
      <form class="form reveal" id="contactForm" novalidate data-ok="<?= e(tx('kontakt.form.ok')) ?>">
        <h2 style="font-size:2rem"><?= t('kontakt.form.title') ?></h2>
        <div id="cfMsg" role="status"></div>
        <div class="row2">
          <div class="field"><label for="cn"><?= t('kontakt.form.name') ?></label><input id="cn" name="name" required autocomplete="name"></div>
          <div class="field"><label for="cp"><?= t('kontakt.form.phone') ?></label><input id="cp" name="phone" type="tel" autocomplete="tel"></div>
        </div>
        <div class="field"><label for="ce"><?= t('kontakt.form.email') ?></label><input id="ce" name="email" type="email" required autocomplete="email"></div>
        <div class="field"><label for="cm"><?= t('kontakt.form.message') ?></label><textarea id="cm" name="message" required></textarea></div>
        <div class="field hp" aria-hidden="true"><label>Web</label><input name="website" tabindex="-1" autocomplete="off"></div>
        <label class="check"><input type="checkbox" name="consent" value="1" required> <span><?= t('kontakt.form.consent') ?> <a href="ochrana-udajov.php" target="_blank"><?= t('kontakt.form.consentlink') ?></a>.</span></label>
        <button class="btn btn-lg" type="submit"><?= t('kontakt.form.btn') ?> <?= icon('arrow') ?></button>
      </form>
    </div>
  </div>
  <div class="wrap split" style="margin-top:60px">
    <div class="reveal left">
      <span class="kicker"><?= t('kontakt.where.kicker') ?></span>
      <h2><?= t('kontakt.where.title') ?></h2>
      <p><?= t('kontakt.where.text') ?></p>
      <p><a href="tel:<?= e($S['phone_raw']) ?>" class="btn"><?= icon('phone') ?> <?= e($S['phone']) ?></a></p>
    </div>
    <div class="split-media reveal right">
      <div class="img-main" style="aspect-ratio:4/3;transform:none"><img src="<?= e(timg('kontakt.where.img')) ?>" alt="<?= e(tx('kontakt.where.title')) ?>" loading="lazy" decoding="async"></div>
    </div>
  </div>
  <div class="wrap" style="margin-top:44px">
    <div class="map reveal">
      <?php if ($S['maps_embed']): ?>
        <div class="map-consent" data-map-src="<?= e($S['maps_embed']) ?>">
          <?= icon('pin', 'ico') ?>
          <p><b><?= e($S['address']) ?></b></p>
          <p class="mc-note">Mapa je zo služby Google Mapy. Po jej zobrazení Google získa vašu IP adresu a môže uložiť cookies. Podrobnosti v <a href="ochrana-udajov.php">zásadách ochrany osobných údajov</a>.</p>
          <p><button type="button" class="btn btn-sm" data-map-allow>Zobraziť mapu</button> <a class="btn btn-ghost btn-sm" href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($S['address']) ?>" target="_blank" rel="noopener"><?= t('kontakt.map') ?></a></p>
        </div>
      <?php else: ?>
        <div style="padding:60px 20px"><?= icon('pin', 'ico') ?><p style="margin:.5em 0 0"><b><?= e($S['address']) ?></b><br><a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($S['address']) ?>" target="_blank" rel="noopener"><?= t('kontakt.map') ?></a></p></div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
