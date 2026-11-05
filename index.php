<?php
$active = 'home';
$title = null;
require __DIR__ . '/inc/header.php';
?>
<section class="hero">
  <span class="blob b1"></span><span class="blob b2"></span><span class="blob b3"></span>
  <div class="wrap hero-grid">
    <div class="hero-text">
      <span class="eyebrow reveal"><?= icon('leaf') ?> Salón pedikúry a ošetrenia chodidiel</span>
      <h1 class="reveal" style="--d:.1s">Krásne a zdravé nohy<span class="script">s láskou a precíznosťou</span></h1>
      <p class="lead reveal" style="--d:.2s">V <b><?= e($S['name']) ?></b> sa o vaše chodidlá postaráme šetrne a profesionálne – od klasickej pedikúry cez brúsenie päty až po ošetrenie mykózy, kurích očí či vrastajúceho nechta.</p>
      <div class="hero-actions reveal" style="--d:.3s">
        <a href="rezervacia.php" class="btn btn-lg">Rezervovať termín <?= icon('arrow') ?></a>
        <a href="sluzby.php" class="btn btn-ghost btn-lg">Naše služby</a>
      </div>
      <div class="hero-badges reveal" style="--d:.4s">
        <span><?= icon('shield') ?> Dôraz na hygienu</span>
        <span><?= icon('heart') ?> Individuálny prístup</span>
        <span><?= icon('clock') ?> Online rezervácia 24/7</span>
      </div>
    </div>
    <div class="hero-visual reveal zoom" style="--d:.2s" id="heroVisual">
      <div class="stage">
        <div class="oval-ring" style="--z:0px"></div>
        <div class="oval" style="--z:40px"><img src="assets/img/pedikura-1.jpg" alt="Upravené nohy po pedikúre"></div>
        <div class="medal" style="--z:110px">
          <svg viewBox="0 0 200 200" class="ring-text" aria-hidden="true"><defs><path id="circ" d="M100,100 m-82,0 a82,82 0 1,1 164,0 a82,82 0 1,1 -164,0"/></defs><text><textPath href="#circ">PediSalón ZK ✿ pedikúra ✿ starostlivosť ✿ </textPath></text></svg>
          <img src="assets/img/logo.jpg" alt="Logo <?= e($S['name']) ?>">
        </div>
        <div class="chip c1" style="--z:130px"><?= icon('clock') ?> Rezervácia online 24/7</div>
        <div class="chip c2" style="--z:100px"><?= icon('heart') ?> Šetrné ošetrenie</div>
      </div>
    </div>
  </div>
</section>

<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    <?php for ($i = 0; $i < 2; $i++): ?>
    <span>Pedikúra</span><span>Brúsenie päty</span><span>Ošetrenie mykózy</span><span>Kurie oká a bradavice</span><span>Špona na vrastajúci necht</span><span>Gél lak</span>
    <?php endfor; ?>
  </div>
</div>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker">Čo pre vás urobíme</span>
      <h2>Naše služby</h2>
      <p>Od relaxačnej pedikúry po ošetrenie problémových chodidiel. Vyberte si, čo vaše nohy potrebujú.</p>
    </div>
    <div class="cards">
      <?php foreach ($C['services'] as $id => $s):
        if ($id === 'pedikura-gel') continue; ?>
      <a class="card" href="<?= e($s['page']) ?>">
        <div class="thumb"><img src="assets/img/<?= e($s['img']) ?>" alt="<?= e($s['name']) ?>" loading="lazy"><div class="badge-ico"><?= icon($s['icon']) ?></div></div>
        <div class="body">
          <h3><?= e($s['name']) ?></h3>
          <p><?= e($s['short']) ?></p>
          <div class="meta"><span class="price">od <?= money($s['price']) ?></span><span class="more">Zistiť viac <?= icon('arrow') ?></span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:44px" class="reveal"><a href="cennik.php" class="btn btn-ghost">Pozrieť celý cenník</a></p>
  </div>
</section>

<section class="section tint">
  <div class="wrap split">
    <div class="split-media reveal left">
      <div class="img-main" data-parallax="-0.02"><img src="assets/img/pedikura-6.jpg" alt="Upravené nohy s bordovým lakom" loading="lazy"></div>
      <div class="img-sec"><img src="assets/img/pedikura-9.jpg" alt="Pedikúra s ružovým lakom" loading="lazy"></div>
      <div class="float-badge"><?= icon('heart') ?><div><b>S láskou</b><small>ku každému klientovi</small></div></div>
    </div>
    <div class="reveal right">
      <span class="kicker" style="font-family:var(--font-script);color:var(--rose-600);font-size:1.7rem">O salóne</span>
      <h2>Starostlivosť, ktorú vaše nohy ocenia</h2>
      <p>Nohy nás nesú celý deň – zaslúžia si pozornosť. V našom salóne sa venujeme estetickej aj problémovej pedikúre. Každé ošetrenie začíname krátkou konzultáciou, aby sme zvolili postup šetrný práve pre vás.</p>
      <ul class="ticks">
        <li><?= icon('check') ?> Šetrné postupy a kvalitná kozmetika</li>
        <li><?= icon('check') ?> Čisté pomôcky a príjemné prostredie</li>
        <li><?= icon('check') ?> Poradenstvo ako sa starať o nohy doma</li>
        <li><?= icon('check') ?> Pohodlné objednanie online</li>
      </ul>
      <a href="o-nas.php" class="btn">Viac o nás <?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker">Ako to prebieha</span>
      <h2>Štyri jednoduché kroky k zdravším nohám</h2>
    </div>
    <ol class="flow" id="flow">
      <li><span class="num">01</span><span class="dot"><?= icon('clock') ?></span><h3>Rezervácia</h3><p>Vyberiete si službu a voľný termín v online kalendári.</p></li>
      <li><span class="num">02</span><span class="dot"><?= icon('heart') ?></span><h3>Konzultácia</h3><p>Pozrieme sa na stav chodidiel a dohodneme vhodný postup.</p></li>
      <li><span class="num">03</span><span class="dot"><?= icon('sparkle') ?></span><h3>Ošetrenie</h3><p>Starostlivo a šetrne sa postaráme o vaše nohy.</p></li>
      <li><span class="num">04</span><span class="dot"><?= icon('leaf') ?></span><h3>Rady domov</h3><p>Dostanete tipy, ako výsledok udržať čo najdlhšie.</p></li>
    </ol>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker">Z nášho salónu</span>
      <h2>Ukážky našej práce</h2>
    </div>
  </div>
  <div class="slider reveal" id="workSlider">
    <div class="track" tabindex="0" aria-label="Ukážky prác – posúvajte do strán">
      <?php foreach (['pedikura-2' => 'Pedikúra', 'pedikura-5' => 'Gél lak', 'brusenie-2' => 'Brúsenie päty', 'pedikura-8' => 'Pedikúra', 'spona-4' => 'Špona', 'pedikura-10' => 'Pedikúra', 'bradavice-2' => 'Kurie oká', 'pedikura-3' => 'Pedikúra', 'mykoza-1' => 'Mykóza', 'pedikura-7' => 'Pedikúra', 'spona-1' => 'Špona', 'pedikura-9' => 'Pedikúra'] as $f => $l): ?>
      <a href="assets/img/<?= $f ?>.jpg" data-lightbox data-label="<?= e($l) ?>" draggable="false"><img src="assets/img/<?= $f ?>.jpg" alt="<?= e($l) ?>" loading="lazy" draggable="false"><span><?= e($l) ?></span></a>
      <?php endforeach; ?>
    </div>
    <div class="wrap slider-ui">
      <div class="bar-progress"><i></i></div>
      <div class="arrows"><button type="button" class="s-prev" aria-label="Späť">‹</button><button type="button" class="s-next" aria-label="Ďalej">›</button></div>
    </div>
  </div>
  <div class="wrap"><p style="text-align:center;margin-top:34px" class="reveal"><a href="galeria.php" class="btn btn-ghost">Celá galéria</a></p></div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head reveal"><span class="kicker">Máte otázky?</span><h2>Časté otázky</h2></div>
    <div class="faq reveal">
      <?php require __DIR__ . '/inc/faq.php'; foreach ($faqGeneral as [$q, $a]): ?>
      <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
