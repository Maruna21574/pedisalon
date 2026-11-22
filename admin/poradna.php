<?php
require __DIR__ . '/_boot.php';

$topics = poradna_data();
$pages = array_merge(service_menu(), [['cennik.php', 'Cenník'], ['rezervacia.php', 'Rezervácia'], ['kontakt.php', 'Kontakt']]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (($_POST['action'] ?? '') === 'reset') {
        @unlink(PORADNA_FILE);
        flash('Témy Poradne boli obnovené na predvolené.');
        redirect('poradna.php');
    }
    $in = (array)($_POST['t'] ?? []);
    $out = []; $used = [];
    foreach ($in as $i => $t) {
        if (!empty($t['del'])) continue;
        $h = trim((string)($t['h'] ?? ''));
        if ($h === '') continue;
        $id = slugify(trim((string)($t['id'] ?? '')) ?: $h) ?: 'tema';
        for ($n = 2; isset($used[$id]); $n++) $id = slugify($h) . '-' . $n;
        $used[$id] = true;
        $icon = isset(ICONS[$t['icon'] ?? '']) ? $t['icon'] : 'foot';
        $page = (string)($t['link_page'] ?? '');
        $label = trim((string)($t['link_label'] ?? ''));
        $out[] = ['o' => (float)str_replace(',', '.', (string)($t['order'] ?? $i + 1)), 'id' => $id, 'icon' => $icon, 'h' => $h,
                  'intro' => trim((string)($t['intro'] ?? '')), 'causes' => lines((string)($t['causes'] ?? '')), 'signs' => lines((string)($t['signs'] ?? '')),
                  'help' => trim((string)($t['help'] ?? '')), 'link' => ($page !== '' && $label !== '') ? [$page, $label] : ['', ''], 'doctor' => trim((string)($t['doctor'] ?? ''))];
    }
    usort($out, fn($x, $y) => $x['o'] <=> $y['o']);
    $out = array_map(function ($r) { unset($r['o']); return $r; }, $out);
    store_mutate(PORADNA_FILE, fn() => ['data' => ['topics' => $out]]);
    flash('Poradňa uložená (' . count($out) . ' tém).');
    redirect('poradna.php');
}

admin_head('Poradňa', 'poradna', '', 'Témy na stránke <b>Poradňa</b> (príčiny, príznaky, čo pomáha). Rozbaľte tému a upravte ju, alebo pridajte novú. Úvodné texty, rady na prevenciu a „Kedy ísť k lekárovi“ upravíte v Texty webu → Poradňa.');
$new = ['id' => '', 'icon' => 'foot', 'h' => '', 'intro' => '', 'causes' => [], 'signs' => [], 'help' => '', 'link' => ['', ''], 'doctor' => ''];
$all = $topics; $all[] = $new;
?>
<form method="post">
  <?= csrf_field() ?><input type="hidden" name="action" value="save">
  <?php foreach ($all as $i => $t): $isNew = $i === count($topics); ?>
  <details class="panel" <?= $isNew ? '' : '' ?>>
    <summary style="cursor:pointer;font-weight:700;font-size:1.05rem"><?= $isNew ? '+ Pridať novú tému' : e($t['h']) ?></summary>
    <div style="margin-top:16px">
      <input type="hidden" name="t[<?= $i ?>][id]" value="<?= e($t['id']) ?>">
      <div class="grid-form">
        <div class="field"><label>Názov témy</label><input name="t[<?= $i ?>][h]" value="<?= e($t['h']) ?>" <?= $isNew ? '' : 'required' ?>></div>
        <div class="field"><label>Ikona</label><select name="t[<?= $i ?>][icon]"><?php foreach (ICONS as $ik => $il): ?><option value="<?= $ik ?>" <?= $t['icon'] === $ik ? 'selected' : '' ?>><?= e($il) ?></option><?php endforeach; ?></select></div>
        <div class="field"><label>Poradie</label><input type="number" step="1" name="t[<?= $i ?>][order]" value="<?= $i + 1 ?>" style="width:6em"></div>
      </div>
      <div class="field"><label>Úvod (1–2 vety o probléme)</label><textarea name="t[<?= $i ?>][intro]" rows="3"><?= e($t['intro']) ?></textarea></div>
      <div class="grid-form">
        <div class="field"><label>Čo to spôsobuje (jeden riadok = jedna príčina)</label><textarea name="t[<?= $i ?>][causes]" rows="6"><?= e(implode("\n", $t['causes'])) ?></textarea></div>
        <div class="field"><label>Ako sa to prejavuje (jeden riadok = jeden príznak)</label><textarea name="t[<?= $i ?>][signs]" rows="6"><?= e(implode("\n", $t['signs'])) ?></textarea></div>
      </div>
      <div class="field"><label>Čo pomáha</label><textarea name="t[<?= $i ?>][help]" rows="3"><?= e($t['help']) ?></textarea></div>
      <div class="field"><label>Upozornenie – kedy k lekárovi</label><textarea name="t[<?= $i ?>][doctor]" rows="2"><?= e($t['doctor']) ?></textarea></div>
      <div class="grid-form">
        <div class="field"><label>Tlačidlo vedie na stránku</label><select name="t[<?= $i ?>][link_page]"><option value="">— bez tlačidla —</option><?php foreach ($pages as [$h, $l]): ?><option value="<?= e($h) ?>" <?= ($t['link'][0] ?? '') === $h ? 'selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?></select></div>
        <div class="field"><label>Text tlačidla</label><input name="t[<?= $i ?>][link_label]" value="<?= e($t['link'][1] ?? '') ?>"></div>
      </div>
      <?php if (!$isNew): ?><label class="check"><input type="checkbox" name="t[<?= $i ?>][del]" value="1"> <span>Zmazať túto tému pri uložení</span></label><?php endif; ?>
    </div>
  </details>
  <?php endforeach; ?>
  <button class="btn">Uložiť Poradňu</button>
</form>

<div class="panel hint" style="margin-top:24px">
  <form method="post" onsubmit="return confirm('Obnoviť predvolené témy Poradne? Vaše zmeny sa stratia.');">
    <?= csrf_field() ?><input type="hidden" name="action" value="reset">
    <p style="margin:0 0 10px">Ak ste si témy pokazili, môžete ich vrátiť na pôvodné.</p>
    <button class="btn btn-sm btn-ghost" type="submit">Obnoviť predvolené témy</button>
  </form>
</div>
<?php admin_foot();
