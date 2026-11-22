<?php
$active = 'rezervacia';
$title = 'Online rezervácia';
$desc = 'Objednajte sa online do PediSalónu ZK – vyberte službu, dátum a čas za pár sekúnd.';
$bodyClass = 'on-booking';
$extraJs = ['assets/js/booking.js?v=2'];
require __DIR__ . '/inc/header.php';
$svcData = [];
foreach ($C['services'] as $id => $s) $svcData[$id] = ['name' => $s['name'], 'duration' => $s['duration'], 'price' => $s['price'], 'img' => img_thumb($s['img']), 'short' => $s['short']];
?>
<section class="page-hero" style="padding-bottom:50px">
  <?= icon('leaf', 'ico leaf l1') ?><?= icon('leaf', 'ico leaf l2') ?>
  <div class="wrap">
    <nav class="crumbs"><a href="index.php"><?= t('nav.home') ?></a> › <span><?= t('rezervacia.hero.title') ?></span></nav>
    <h1 class="reveal"><?= t('rezervacia.hero.title') ?></h1>
    <p class="reveal" style="--d:.1s"><?= t('rezervacia.hero.lead') ?></p>
  </div>
</section>

<section class="section" style="padding-top:50px">
  <div class="wrap booking" id="booking"
       data-config='<?= e(json_encode([
         'services' => $svcData,
         'maxDays' => $C['booking']['max_days_ahead'],
         'autoConfirm' => $C['booking']['auto_confirm'],
         'phone' => $S['phone'],
         'texts' => ['unset' => tx('rez.sum.unset'), 'confirmed' => tx('rez.done.confirmed'), 'pending' => tx('rez.done.pending'), 'note' => tx('rez.done.note'), 'min' => tx('common.min')],
       ], JSON_UNESCAPED_UNICODE)) ?>'>
    <div class="bk-main">
      <ol class="stepper" id="stepper">
        <?php foreach (array_slice(array_pad(tl('rez.steps'), 4, ''), 0, 4) as $stepName): ?><li><?= e($stepName) ?></li><?php endforeach; ?>
      </ol>

      <!-- 1 -->
      <div class="bk-step" data-step="1">
        <h2><?= t('rez.h.service') ?></h2>
        <div class="svc-pick" id="svcPick">
          <?php foreach (service_categories() as $cat => $items): ?>
          <h3 class="svc-cat"><?= e($cat) ?></h3>
          <?php foreach ($items as $id => $s): ?>
          <button type="button" class="svc-opt" data-id="<?= e($id) ?>">
            <img src="<?= e(img_thumb($s['img'])) ?>" alt="" loading="lazy" decoding="async">
            <span><b><?= e($s['name']) ?></b><small><?= (int)$s['duration'] ?> <?= t('common.min') ?></small></span>
            <span class="p"><?= money($s['price']) ?></span>
          </button>
          <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 2 -->
      <div class="bk-step" data-step="2">
        <h2><?= t('rez.h.date') ?></h2>
        <div class="cal-head">
          <button type="button" class="cal-nav" id="calPrev" aria-label="Predchádzajúci mesiac">‹</button>
          <h3 id="calTitle"></h3>
          <button type="button" class="cal-nav" id="calNext" aria-label="Nasledujúci mesiac">›</button>
        </div>
        <div class="cal" id="cal" aria-live="polite"></div>
        <div class="legend"><span><i style="background:var(--sage-100)"></i><?= t('rez.legend.free') ?></span><span><i style="background:var(--sage-50);border:1px solid var(--sage-200)"></i><?= t('rez.legend.busy') ?></span></div>
        <div class="bk-nav"><button type="button" class="btn btn-ghost" data-back><?= t('rez.back') ?></button><span></span></div>
      </div>

      <!-- 3 -->
      <div class="bk-step" data-step="3">
        <h2><?= t('rez.h.time') ?></h2>
        <p id="slotDate" style="color:var(--muted);font-weight:600"></p>
        <div class="slots" id="slots"></div>
        <div class="bk-nav"><button type="button" class="btn btn-ghost" data-back><?= t('rez.back') ?></button><span></span></div>
      </div>

      <!-- 4 -->
      <div class="bk-step" data-step="4">
        <h2><?= t('rez.h.data') ?></h2>
        <div id="formMsg" role="alert"></div>
        <form id="bkForm" novalidate>
          <div class="field"><label for="f-name"><?= t('rez.f.name') ?></label><input id="f-name" name="name" autocomplete="name" required><div class="err"></div></div>
          <div class="row2">
            <div class="field"><label for="f-phone"><?= t('rez.f.phone') ?></label><input id="f-phone" name="phone" type="tel" autocomplete="tel" placeholder="+421 9xx xxx xxx" required><div class="err"></div></div>
            <div class="field"><label for="f-email"><?= t('rez.f.email') ?></label><input id="f-email" name="email" type="email" autocomplete="email"><div class="err"></div></div>
          </div>
          <div class="field"><label for="f-note"><?= t('rez.f.note') ?></label><textarea id="f-note" name="note" placeholder="<?= e(tx('rez.f.noteph')) ?>"></textarea><small style="color:var(--muted)">Údaje o zdraví uvádzajte len ak sú nevyhnutné pre bezpečné ošetrenie. Viac v <a href="ochrana-udajov.php" target="_blank">ochrane osobných údajov</a>.</small><div class="err"></div></div>
          <div class="field hp" aria-hidden="true"><label>Web</label><input name="website" tabindex="-1" autocomplete="off"></div>
          <div class="field"><label class="check" style="margin:0"><input type="checkbox" name="consent" value="1"> <span><?= t('rez.consent') ?> <a href="ochrana-udajov.php" target="_blank"><?= t('rez.consentlink') ?></a>. *</span></label><div class="err"></div></div>
          <div class="bk-nav"><button type="button" class="btn btn-ghost" data-back><?= t('rez.back') ?></button><button type="submit" class="btn btn-lg" id="bkSubmit"><?= t('rez.submit') ?> <?= icon('check') ?></button></div>
        </form>
      </div>

      <!-- Hotovo -->
      <div class="bk-step" data-step="done">
        <div class="success">
          <svg class="tick" viewBox="0 0 120 120"><circle cx="60" cy="60" r="48"/><path d="M38 62l15 15 30-34"/></svg>
          <h2><?= t('rez.done.title') ?></h2>
          <p id="doneText"></p>
          <div class="box" id="doneBox"></div>
          <p style="color:var(--muted);font-size:.95rem" id="doneNote"></p>
          <p><a href="index.php" class="btn"><?= t('rez.done.btn') ?></a></p>
        </div>
      </div>
    </div>

    <aside class="bk-summary" aria-label="Zhrnutie rezervácie">
      <h3><?= t('rez.sum.title') ?></h3>
      <dl>
        <dt><?= t('rez.sum.service') ?></dt><dd id="sumSvc" class="empty"><?= t('rez.sum.unset') ?></dd>
        <dt><?= t('rez.sum.when') ?></dt><dd id="sumWhen" class="empty"><?= t('rez.sum.unset') ?></dd>
        <dt><?= t('rez.sum.dur') ?></dt><dd id="sumDur" class="empty">–</dd>
      </dl>
      <div class="total"><span><?= t('rez.sum.price') ?></span><b id="sumPrice">–</b></div>
      <p><?= t('rez.sum.note') ?></p>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
