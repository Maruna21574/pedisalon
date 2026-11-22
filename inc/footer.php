</main>

<section class="cta-band reveal">
  <div class="wrap">
    <div>
      <h2><?= t('cta.title') ?></h2>
      <p><?= t('cta.text') ?></p>
    </div>
    <a href="rezervacia.php" class="btn btn-light btn-lg"><?= t('nav.book') ?> <?= icon('arrow') ?></a>
  </div>
</section>

<footer class="site-footer">
  <div class="wrap grid">
    <div class="f-brand">
      <img src="assets/img/logo.png" alt="Logo <?= e($S['name']) ?>" width="220" height="123" loading="lazy" decoding="async">
      <p><?= e($S['tagline']) ?>. <?= t('footer.about') ?></p>
      <div class="social">
        <a href="<?= e($S['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('fb') ?></a>
        <a href="<?= e($S['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('ig') ?></a>
      </div>
    </div>
    <div>
      <h3><?= t('footer.h.services') ?></h3>
      <ul>
        <?php foreach ($serviceMenu as [$h, $l]): ?><li><a href="<?= e($h) ?>"><?= e($l) ?></a></li><?php endforeach; ?>
        <li><a href="cennik.php"><?= t('nav.pricing') ?></a></li>
      </ul>
    </div>
    <div>
      <h3><?= t('footer.h.salon') ?></h3>
      <ul>
        <li><a href="o-nas.php"><?= t('nav.about') ?></a></li>
        <li><a href="prevadzka.php"><?= t('nav.premises') ?></a></li>
        <li><a href="galeria.php"><?= t('nav.gallery') ?></a></li>
        <li><a href="poradna.php">Poradňa</a></li>
        <li><a href="rezervacia.php"><?= t('footer.l.booking') ?></a></li>
        <li><a href="kontakt.php"><?= t('nav.contact') ?></a></li>
        <li><a href="ochrana-udajov.php"><?= t('footer.l.privacy') ?></a></li>
        <li><a href="cookies.php">Cookies</a></li>
        <li><button type="button" class="linklike" data-cookie-settings>Nastavenia cookies</button></li>
      </ul>
    </div>
    <div>
      <h3><?= t('footer.h.contact') ?></h3>
      <ul class="contact">
        <li><?= icon('phone') ?><a href="tel:<?= e($S['phone_raw']) ?>"><?= e($S['phone']) ?></a></li>
        <li><?= icon('mail') ?><a href="mailto:<?= e($S['email']) ?>"><?= e($S['email']) ?></a></li>
        <li><?= icon('pin') ?><span><?= e($S['address']) ?></span></li>
        <li><?= icon('clock') ?><span><?= hours_summary() ?></span></li>
      </ul>
    </div>
  </div>
  <div class="wrap legal">
    <span>© <?= date('Y') ?> <?= e($S['name']) ?>. <?= t('footer.rights') ?><?= $S['ico'] ? ' ' . e($S['ico']) : '' ?></span>
    <span><?= t('footer.legal') ?></span>
  </div>
</footer>

<a href="rezervacia.php" class="fab" aria-label="<?= e(tx('nav.book')) ?>"><?= icon('clock') ?><span><?= t('fab.book') ?></span></a>

<canvas id="petals" aria-hidden="true"></canvas>
<div class="cookie-bar" id="cookieBar" role="dialog" aria-live="polite" aria-label="Súhlas s cookies" hidden>
  <div class="cb-main">
    <p><b>Vaše súkromie</b><br>Tento web používa len nevyhnutné technické údaje. Voliteľne môžete povoliť externú službu <b>Google Mapy</b> (zobrazenie mapy v Kontakte). Bez vášho súhlasu sa nič z Google nenačíta. Viac v <a href="ochrana-udajov.php">ochrane osobných údajov</a> a <a href="cookies.php">zásadách cookies</a>.</p>
    <div class="cb-btns">
      <button type="button" class="btn btn-sm" data-cb="all">Prijať všetko</button>
      <button type="button" class="btn btn-ghost btn-sm" data-cb="necessary">Len nevyhnutné</button>
      <button type="button" class="linklike" data-cb="custom">Nastavenia</button>
    </div>
  </div>
  <div class="cb-details" hidden>
    <label class="check"><input type="checkbox" checked disabled> <span><b>Nevyhnutné</b> – zapamätanie vašej voľby cookies a bezpečné fungovanie webu. Nedajú sa vypnúť.</span></label>
    <label class="check"><input type="checkbox" name="maps"> <span><b>Externé služby – Google Mapy</b> – zobrazenie mapy v Kontakte (Google získa vašu IP adresu a môže uložiť cookies).</span></label>
    <button type="button" class="btn btn-sm" data-cb="save">Uložiť moju voľbu</button>
  </div>
</div>
<script src="assets/js/consent.js?v=1" defer></script>
<script src="assets/js/hero.js?v=3" defer></script>
<script src="assets/js/main.js?v=4" defer></script>
<?php if (!empty($extraJs)) foreach ($extraJs as $js): ?>
<script src="<?= e($js) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
