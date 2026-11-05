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
    <nav class="crumbs"><a href="index.php">Domov</a> › <span>Kontakt</span></nav>
    <h1 class="reveal">Kontakt</h1>
    <p class="reveal" style="--d:.1s">Radi vám odpovieme na otázky. Ozvite sa nám alebo sa rovno objednajte online.</p>
  </div>
</section>

<section class="section">
  <div class="wrap contact-grid">
    <div>
      <ul class="info-list">
        <li><span class="ic"><?= icon('phone') ?></span><div><b>Telefón</b><a href="tel:<?= e($S['phone_raw']) ?>"><?= e($S['phone']) ?></a></div></li>
        <li><span class="ic"><?= icon('mail') ?></span><div><b>E-mail</b><a href="mailto:<?= e($S['email']) ?>"><?= e($S['email']) ?></a></div></li>
        <li><span class="ic"><?= icon('pin') ?></span><div><b>Adresa</b><?= e($S['address']) ?></div></li>
        <li><span class="ic"><?= icon('clock') ?></span><div style="flex:1"><b>Otváracie hodiny</b>
          <table class="hours-table">
            <?php foreach (DAY_NAMES as $d => $n): $h = $C['hours'][$d]; ?>
            <tr class="<?= $d === $today ? 'today' : '' ?>"><td><?= $n ?></td><td><?= $h ? $h[0] . ' – ' . $h[1] : 'Zatvorené' ?></td></tr>
            <?php endforeach; ?>
          </table></div></li>
      </ul>
    </div>
    <div>
      <form class="form reveal" id="contactForm" novalidate>
        <h2 style="font-size:2rem">Napíšte nám</h2>
        <div id="cfMsg" role="status"></div>
        <div class="row2">
          <div class="field"><label for="cn">Meno</label><input id="cn" name="name" required autocomplete="name"></div>
          <div class="field"><label for="cp">Telefón</label><input id="cp" name="phone" type="tel" autocomplete="tel"></div>
        </div>
        <div class="field"><label for="ce">E-mail</label><input id="ce" name="email" type="email" required autocomplete="email"></div>
        <div class="field"><label for="cm">Správa</label><textarea id="cm" name="message" required></textarea></div>
        <div class="field hp" aria-hidden="true"><label>Web</label><input name="website" tabindex="-1" autocomplete="off"></div>
        <label class="check"><input type="checkbox" name="consent" value="1" required> <span>Súhlasím so spracovaním osobných údajov podľa <a href="ochrana-udajov.php" target="_blank">zásad ochrany údajov</a>.</span></label>
        <button class="btn btn-lg" type="submit">Odoslať správu <?= icon('arrow') ?></button>
      </form>
    </div>
  </div>
  <div class="wrap" style="margin-top:44px">
    <div class="map reveal">
      <?php if ($S['maps_embed']): ?>
        <iframe src="<?= e($S['maps_embed']) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa"></iframe>
      <?php else: ?>
        <div style="padding:60px 20px"><?= icon('pin', 'ico') ?><p style="margin:.5em 0 0"><b><?= e($S['address']) ?></b><br><a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($S['address']) ?>" target="_blank" rel="noopener">Otvoriť v Mapách Google →</a></p></div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
