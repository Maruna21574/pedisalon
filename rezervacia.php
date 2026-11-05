<?php
$active = 'rezervacia';
$title = 'Online rezervácia';
$desc = 'Objednajte sa online do PediSalónu ZK – vyberte službu, dátum a čas za pár sekúnd.';
$bodyClass = 'on-booking';
$extraJs = ['assets/js/booking.js?v=1'];
require __DIR__ . '/inc/header.php';
$svcData = [];
foreach ($C['services'] as $id => $s) $svcData[$id] = ['name' => $s['name'], 'duration' => $s['duration'], 'price' => $s['price'], 'img' => 'assets/img/' . $s['img'], 'short' => $s['short']];
?>
<section class="page-hero" style="padding-bottom:50px">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php">Domov</a> › <span>Rezervácia</span></nav>
    <h1 class="reveal">Online rezervácia</h1>
    <p class="reveal" style="--d:.1s">Vyberte si službu, deň a čas. Rezervácia trvá menej ako minútu.</p>
  </div>
</section>

<section class="section" style="padding-top:50px">
  <div class="wrap booking" id="booking"
       data-config='<?= e(json_encode([
         'services' => $svcData,
         'maxDays' => $C['booking']['max_days_ahead'],
         'autoConfirm' => $C['booking']['auto_confirm'],
         'phone' => $S['phone'],
       ], JSON_UNESCAPED_UNICODE)) ?>'>
    <div class="bk-main">
      <ol class="stepper" id="stepper">
        <li>Služba</li><li>Dátum</li><li>Čas</li><li>Údaje</li>
      </ol>

      <!-- 1 -->
      <div class="bk-step" data-step="1">
        <h2>Vyberte službu</h2>
        <div class="svc-pick" id="svcPick">
          <?php foreach ($C['services'] as $id => $s): ?>
          <button type="button" class="svc-opt" data-id="<?= e($id) ?>">
            <img src="assets/img/<?= e($s['img']) ?>" alt="" loading="lazy">
            <span><b><?= e($s['name']) ?></b><small><?= (int)$s['duration'] ?> min</small></span>
            <span class="p"><?= money($s['price']) ?></span>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 2 -->
      <div class="bk-step" data-step="2">
        <h2>Vyberte dátum</h2>
        <div class="cal-head">
          <button type="button" class="cal-nav" id="calPrev" aria-label="Predchádzajúci mesiac">‹</button>
          <h3 id="calTitle"></h3>
          <button type="button" class="cal-nav" id="calNext" aria-label="Nasledujúci mesiac">›</button>
        </div>
        <div class="cal" id="cal" aria-live="polite"></div>
        <div class="legend"><span><i style="background:var(--sage-100)"></i>voľné termíny</span><span><i style="background:var(--sage-50);border:1px solid var(--sage-200)"></i>obsadené / zatvorené</span></div>
        <div class="bk-nav"><button type="button" class="btn btn-ghost" data-back>‹ Späť</button><span></span></div>
      </div>

      <!-- 3 -->
      <div class="bk-step" data-step="3">
        <h2>Vyberte čas</h2>
        <p id="slotDate" style="color:var(--muted);font-weight:600"></p>
        <div class="slots" id="slots"></div>
        <div class="bk-nav"><button type="button" class="btn btn-ghost" data-back>‹ Späť</button><span></span></div>
      </div>

      <!-- 4 -->
      <div class="bk-step" data-step="4">
        <h2>Vaše údaje</h2>
        <div id="formMsg" role="alert"></div>
        <form id="bkForm" novalidate>
          <div class="field"><label for="f-name">Meno a priezvisko *</label><input id="f-name" name="name" autocomplete="name" required><div class="err"></div></div>
          <div class="row2">
            <div class="field"><label for="f-phone">Telefón *</label><input id="f-phone" name="phone" type="tel" autocomplete="tel" placeholder="+421 9xx xxx xxx" required><div class="err"></div></div>
            <div class="field"><label for="f-email">E-mail (pre potvrdenie)</label><input id="f-email" name="email" type="email" autocomplete="email"><div class="err"></div></div>
          </div>
          <div class="field"><label for="f-note">Poznámka (nepovinné)</label><textarea id="f-note" name="note" placeholder="Napr. zdravotné ťažkosti, alergie, preferovaný odtieň lakovania…"></textarea><div class="err"></div></div>
          <div class="field hp" aria-hidden="true"><label>Web</label><input name="website" tabindex="-1" autocomplete="off"></div>
          <div class="field"><label class="check" style="margin:0"><input type="checkbox" name="consent" value="1"> <span>Súhlasím so spracovaním osobných údajov na účely rezervácie podľa <a href="ochrana-udajov.php" target="_blank">zásad ochrany údajov</a>. *</span></label><div class="err"></div></div>
          <div class="bk-nav"><button type="button" class="btn btn-ghost" data-back>‹ Späť</button><button type="submit" class="btn btn-lg" id="bkSubmit">Potvrdiť rezerváciu <?= icon('check') ?></button></div>
        </form>
      </div>

      <!-- Hotovo -->
      <div class="bk-step" data-step="done">
        <div class="success">
          <svg class="tick" viewBox="0 0 120 120"><circle cx="60" cy="60" r="48"/><path d="M38 62l15 15 30-34"/></svg>
          <h2>Ďakujeme, rezervácia prijatá!</h2>
          <p id="doneText"></p>
          <div class="box" id="doneBox"></div>
          <p style="color:var(--muted);font-size:.95rem" id="doneNote"></p>
          <p><a href="index.php" class="btn">Späť na úvod</a></p>
        </div>
      </div>
    </div>

    <aside class="bk-summary" aria-label="Zhrnutie rezervácie">
      <h3>Vaša rezervácia</h3>
      <dl>
        <dt>Služba</dt><dd id="sumSvc" class="empty">Zatiaľ nevybraté</dd>
        <dt>Termín</dt><dd id="sumWhen" class="empty">Zatiaľ nevybraté</dd>
        <dt>Trvanie</dt><dd id="sumDur" class="empty">–</dd>
      </dl>
      <div class="total"><span>Cena od</span><b id="sumPrice">–</b></div>
      <p>Platba prebieha v salóne. Rezerváciu môžete zrušiť telefonicky na <b><?= e($S['phone']) ?></b>.</p>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
