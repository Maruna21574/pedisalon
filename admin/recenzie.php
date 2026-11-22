<?php
require __DIR__ . '/_boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $url = trim((string)($_POST['url'] ?? ''));
    if ($url !== '' && !preg_match('#^https://#i', $url)) { flash('Odkaz na recenzie musí začínať https://.', 'bad'); redirect('recenzie.php'); }

    $items = []; $orders = [];
    foreach ($_POST['it'] ?? [] as $i => $in) {
        if (!empty($in['delete'])) continue;
        $text = trim((string)($in['text'] ?? '')); $name = trim((string)($in['name'] ?? ''));
        if ($text === '' || $name === '') continue;
        $items[] = ['name' => mb_substr($name, 0, 60), 'stars' => max(1, min(5, (int)($in['stars'] ?? 5))), 'when' => mb_substr(trim((string)($in['when'] ?? '')), 0, 40), 'text' => mb_substr($text, 0, 700)];
        $orders[] = (int)($in['order'] ?? 0);
    }
    $n = $_POST['new'] ?? [];
    if (trim((string)($n['text'] ?? '')) !== '' && trim((string)($n['name'] ?? '')) !== '') {
        $items[] = ['name' => mb_substr(trim($n['name']), 0, 60), 'stars' => max(1, min(5, (int)($n['stars'] ?? 5))), 'when' => mb_substr(trim((string)($n['when'] ?? '')), 0, 40), 'text' => mb_substr(trim($n['text']), 0, 700)];
        $orders[] = 100000;
    }
    array_multisort($orders, SORT_ASC, $items);
    $enabled = !empty($_POST['enabled']) && $items;
    store_mutate(REVIEWS_FILE, fn(array $old) => ['data' => [
        'enabled' => (bool)$enabled, 'url' => $url, 'rating' => trim((string)($_POST['rating'] ?? '')), 'count' => trim((string)($_POST['count'] ?? '')), 'items' => $items,
    ]]);
    flash($enabled ? 'Recenzie uložené a zobrazené na úvodnej stránke.' : 'Recenzie uložené. Sekcia je na webe skrytá.');
    redirect('recenzie.php');
}

$R = reviews_data();
$demo = !empty($R['demo']);
admin_head('Recenzie z Google', 'recenzie', '', 'Slider s recenziami na úvodnej stránke (s logom Google). Nadpisy sekcie upravíte v <a href="texty.php">Texty webu</a>.');
?>
<div class="alert bad" style="background:#fff7e0;border-color:#f0d58a;color:#7a5a00">
  <b>Zverejňujte len skutočné recenzie.</b> Vymyslené alebo upravené hodnotenia pod značkou Google sú klamlivé a podľa spotrebiteľského práva (EÚ aj SR) zakázané.
  Skopírujte recenzie zo svojho Google profilu tak, ako ich klienti napísali.
  <?php if ($demo): ?><br>Zatiaľ sú tu len <b>testovacie texty</b> a sekcia je na webe <b>skrytá</b>. Nahraďte ich skutočnými recenziami z Google a zapnite zobrazenie.<?php endif; ?>
</div>

<form method="post">
  <?= csrf_field() ?>
  <div class="panel">
    <h2>Zobrazenie</h2>
    <label class="check"><input type="checkbox" name="enabled" value="1" <?= !empty($R['enabled']) ? 'checked' : '' ?>> <span><b>Zobraziť sekciu recenzií na úvodnej stránke</b></span></label>
    <div class="grid-form">
      <div class="field"><label>Priemerné hodnotenie (podľa Google)</label><input name="rating" value="<?= e($R['rating']) ?>" placeholder="napr. 4,9"><small class="help">Nechajte prázdne, ak ho nechcete zobrazovať.</small></div>
      <div class="field"><label>Počet recenzií na Google</label><input name="count" value="<?= e($R['count']) ?>" placeholder="napr. 27"></div>
    </div>
    <div class="field"><label>Odkaz na vaše recenzie na Google (tlačidlo pod sliderom)</label><input name="url" value="<?= e($R['url']) ?>" placeholder="https://g.page/r/…"><small class="help">V Google Business profile: „Získať viac recenzií“ → skopírovať odkaz.</small></div>
  </div>

  <div class="panel">
    <h2>Recenzie (<?= count($R['items']) ?>)</h2>
    <?php foreach ($R['items'] as $i => $it): ?>
    <div class="rev-edit">
      <div class="grid-form">
        <div class="field"><label>Meno klienta</label><input name="it[<?= $i ?>][name]" value="<?= e($it['name']) ?>" maxlength="60"></div>
        <div class="field"><label>Hviezdičky</label><select name="it[<?= $i ?>][stars]"><?php for ($s = 5; $s >= 1; $s--): ?><option value="<?= $s ?>" <?= (int)$it['stars'] === $s ? 'selected' : '' ?>><?= $s ?> ★</option><?php endfor; ?></select></div>
        <div class="field"><label>Kedy</label><input name="it[<?= $i ?>][when]" value="<?= e($it['when']) ?>" maxlength="40" placeholder="pred mesiacom"></div>
        <div class="field"><label>Poradie</label><input type="number" name="it[<?= $i ?>][order]" value="<?= ($i + 1) * 10 ?>"></div>
      </div>
      <div class="field"><label>Text recenzie</label><textarea name="it[<?= $i ?>][text]" rows="3" maxlength="700"><?= e($it['text']) ?></textarea></div>
      <label class="check del-check"><input type="checkbox" name="it[<?= $i ?>][delete]" value="1"> <span>Zmazať túto recenziu</span></label>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="panel svc-edit new">
    <h2>+ Pridať recenziu</h2>
    <div class="grid-form">
      <div class="field"><label>Meno klienta</label><input name="new[name]" maxlength="60"></div>
      <div class="field"><label>Hviezdičky</label><select name="new[stars]"><?php for ($s = 5; $s >= 1; $s--): ?><option value="<?= $s ?>"><?= $s ?> ★</option><?php endfor; ?></select></div>
      <div class="field"><label>Kedy</label><input name="new[when]" maxlength="40" placeholder="pred mesiacom"></div>
    </div>
    <div class="field"><label>Text recenzie</label><textarea name="new[text]" rows="3" maxlength="700"></textarea></div>
  </div>

  <div class="savebar"><button class="btn">Uložiť recenzie</button></div>
</form>
<?php admin_foot();
