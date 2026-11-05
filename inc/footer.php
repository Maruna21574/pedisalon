</main>

<section class="cta-band reveal">
  <div class="wrap">
    <div>
      <h2>Doprajte svojim nohám starostlivosť, ktorú si zaslúžia</h2>
      <p>Objednajte sa online za pár sekúnd – vyberiete si službu, deň aj čas.</p>
    </div>
    <a href="rezervacia.php" class="btn btn-light btn-lg">Rezervovať termín <?= icon('arrow') ?></a>
  </div>
</section>

<footer class="site-footer">
  <div class="wrap grid">
    <div class="f-brand">
      <img src="assets/img/logo.jpg" alt="Logo <?= e($S['name']) ?>" width="120" height="120" loading="lazy">
      <p><?= e($S['tagline']) ?>. Profesionálna pedikúra a ošetrenie problémových chodidiel v príjemnom prostredí.</p>
      <div class="social">
        <a href="<?= e($S['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('fb') ?></a>
        <a href="<?= e($S['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('ig') ?></a>
      </div>
    </div>
    <div>
      <h3>Služby</h3>
      <ul>
        <?php foreach ($serviceMenu as [$h, $l]): ?><li><a href="<?= $h ?>"><?= $l ?></a></li><?php endforeach; ?>
        <li><a href="cennik.php">Cenník</a></li>
      </ul>
    </div>
    <div>
      <h3>Salón</h3>
      <ul>
        <li><a href="o-nas.php">O nás</a></li>
        <li><a href="galeria.php">Galéria</a></li>
        <li><a href="rezervacia.php">Online rezervácia</a></li>
        <li><a href="kontakt.php">Kontakt</a></li>
        <li><a href="ochrana-udajov.php">Ochrana osobných údajov</a></li>
      </ul>
    </div>
    <div>
      <h3>Kontakt</h3>
      <ul class="contact">
        <li><?= icon('phone') ?><a href="tel:<?= e($S['phone_raw']) ?>"><?= e($S['phone']) ?></a></li>
        <li><?= icon('mail') ?><a href="mailto:<?= e($S['email']) ?>"><?= e($S['email']) ?></a></li>
        <li><?= icon('pin') ?><span><?= e($S['address']) ?></span></li>
        <li><?= icon('clock') ?><span><?= hours_summary() ?></span></li>
      </ul>
    </div>
  </div>
  <div class="wrap legal">
    <span>© <?= date('Y') ?> <?= e($S['name']) ?>. Všetky práva vyhradené.<?= $S['ico'] ? ' ' . e($S['ico']) : '' ?></span>
    <span>Informácie na webe majú informatívny charakter a nenahrádzajú lekárske vyšetrenie.</span>
  </div>
</footer>

<a href="rezervacia.php" class="fab" aria-label="Rezervovať termín"><?= icon('clock') ?><span>Rezervovať</span></a>

<canvas id="petals" aria-hidden="true"></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
<script src="assets/js/hero3d.js?v=5" defer></script>
<script src="assets/js/main.js?v=2" defer></script>
<?php if (!empty($extraJs)) foreach ($extraJs as $js): ?>
<script src="<?= e($js) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
