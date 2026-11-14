<?php
require __DIR__ . '/_boot.php';

$reg = texts_registry();
const PAGE_URLS = ['Celý web' => 'index.php', 'Úvodná stránka' => 'index.php', 'O nás' => 'o-nas.php', 'Služby a cenník' => 'sluzby.php', 'Galéria' => 'galeria.php',
                   'Kontakt' => 'kontakt.php', 'Online rezervácia' => 'rezervacia.php', 'Ochrana údajov a chybová stránka' => 'ochrana-udajov.php'];
const PAGE_HINTS = ['Celý web' => 'Menu, tlačidlá, pätička a e-maily – zobrazujú sa na všetkých stránkach.',
                    'Služby a cenník' => 'Nadpisy a texty stránok Služby a Cenník. Ceny, popisy a podstránky jednotlivých služieb upravíte v sekcii „Služby a ceny“.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $saved = []; $uploadErrors = [];
    foreach ($reg as $key => $def) {
        $field = 'f_' . str_replace('.', '__', $key);
        if ($def[2] === 'photos') {                       // výber viacerých fotiek + nahrávanie nových
            if (!isset($_POST[$field . '_present']) || !empty($_POST['reset'][$field])) continue;
            $sel = array_values(array_filter((array)($_POST[$field] ?? []), fn($p) => is_string($p) && in_array($p, media_list(), true)));
            foreach ($_FILES[$field . '_up']['name'] ?? [] as $i => $_) {
                [$path, $err] = save_upload(['name' => $_FILES[$field . '_up']['name'][$i], 'tmp_name' => $_FILES[$field . '_up']['tmp_name'][$i], 'error' => $_FILES[$field . '_up']['error'][$i], 'size' => $_FILES[$field . '_up']['size'][$i]], $def[0]);
                if ($err) $uploadErrors[] = $err; elseif ($path) $sel[] = $path;
            }
            if ($sel) $saved[$key] = implode("
", $sel);
            continue;
        }
        if (!isset($_POST[$field])) continue;
        if (!empty($_POST['reset'][$field])) continue;     // „vrátiť pôvodný text“
        $v = trim(str_replace("\r\n", "\n", (string)$_POST[$field]));
        if ($def[2] === 'image') {
            if ($v === '') continue;                          // prázdne = predvolené (napr. zástupný rámik)
            $v = img_file($v);
            if (!in_array($v, media_list(), true)) continue;
            if ($v === img_file($def[1])) continue;       // rovnaké ako predvolené – neukladáme
        } elseif ($v === '' || $v === trim(str_replace("\r\n", "\n", $def[1]))) {
            continue;                                      // prázdne alebo nezmenené = predvolený text
        }
        $saved[$key] = $v;
    }
    store_mutate(TEXTS_FILE, fn(array $old) => ['data' => $saved]);
    flash('Texty uložené (' . count($saved) . ' upravených).' . ($uploadErrors ? ' Pozor: ' . implode(' ', array_unique($uploadErrors)) : ''), $uploadErrors ? 'bad' : 'ok');
    redirect('texty.php');
}

$ov = store_read(TEXTS_FILE);
$tree = [];
foreach ($reg as $key => $def) $tree[$def['group']][$def['section']][$key] = $def;
$sid = fn(string $page, string $section) => 'sec-' . slugify($page . ' ' . $section);

admin_head('Texty webu', 'texty', '', 'Tu zmeníte <b>všetky texty na webe</b>. Vľavo vyberte stránku a sekciu, alebo použite vyhľadávanie. Ak pole vymažete alebo zaškrtnete „Vrátiť pôvodný text“, použije sa pôvodné znenie. Texty konkrétnych služieb upravíte v sekcii <a href="sluzby.php">Služby a ceny</a>.');
?>
<div class="texts-layout">
  <aside class="texts-nav">
    <input type="search" id="tSearch" placeholder="Hľadať text…" aria-label="Hľadať text">
    <div id="tNone" class="help" style="display:none">Nič sa nenašlo.</div>
    <?php foreach ($tree as $page => $sections): ?>
      <div class="tn-page">
        <b><?= e($page) ?></b>
        <?php foreach ($sections as $section => $items): ?>
          <a href="#<?= e($sid($page, $section)) ?>"><?= e($section) ?></a>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </aside>

  <form method="post" id="textsForm" class="texts-main" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php foreach ($tree as $page => $sections): ?>
      <h2 class="tpage">
        <?= e($page) ?>
        <?php if (isset(PAGE_URLS[$page])): ?><a class="btn btn-sm btn-ghost" href="../<?= PAGE_URLS[$page] ?>" target="_blank">Zobraziť stránku ↗</a><?php endif; ?>
      </h2>
      <?php if (isset(PAGE_HINTS[$page])): ?><p class="help" style="margin:-6px 0 14px"><?= e(PAGE_HINTS[$page]) ?></p><?php endif; ?>
      <?php foreach ($sections as $section => $items): ?>
      <details class="panel tsec" id="<?= e($sid($page, $section)) ?>" open>
        <summary><?= e($section) ?> <span class="count"><?= count($items) ?></span></summary>
        <?php foreach ($items as $key => $def):
          [$label, $default, $type] = $def;
          $field = 'f_' . str_replace('.', '__', $key);
          $changed = ($ov[$key] ?? '') !== '';
          $val = $changed ? $ov[$key] : $default;
        ?>
        <div class="field tfield" data-search="<?= e(mb_strtolower($label . ' ' . $val)) ?>">
          <label for="<?= e($field) ?>"><?= e($label) ?> <?php if ($changed): ?><span class="badge-edit">upravené</span><?php endif; ?></label>
          <?php if ($type === 'photos'):
            $all = media_list(); $selected = array_values(array_filter(array_map('img_file', lines($val)), fn($p) => in_array($p, $all, true)));
            $ordered = array_merge($selected, array_values(array_diff($all, $selected))); ?>
            <input type="hidden" name="<?= e($field) ?>_present" value="1">
            <div class="media-grid">
              <?php foreach ($ordered as $mf): ?>
              <label class="media-item <?= in_array($mf, $selected, true) ? 'on' : '' ?>"><input type="checkbox" name="<?= e($field) ?>[]" value="<?= e($mf) ?>" <?= in_array($mf, $selected, true) ? 'checked' : '' ?>><img src="../assets/img/<?= e($mf) ?>" alt="" loading="lazy"><span><?= e(basename($mf)) ?></span></label>
              <?php endforeach; ?>
            </div>
            <small class="help">Zaškrtnuté fotky sa zobrazia v tomto poradí. Nič nezaškrtnuté = zástupné rámiky.</small>
            <div style="margin-top:10px"><label style="font-weight:600">Nahrať nové fotky:</label> <input type="file" name="<?= e($field) ?>_up[]" multiple accept="image/jpeg,image/png,image/webp"></div>
          <?php elseif ($type === 'image'): ?>
            <?= image_picker($field, $val, 'pv_' . $field, $default === '') ?>
          <?php elseif ($type === 'text'): ?>
            <input id="<?= e($field) ?>" name="<?= e($field) ?>" value="<?= e($val) ?>">
          <?php else:
            $rows = ['area' => 3, 'lines' => 5, 'pairs' => 6, 'rich' => 12][$type] ?? 3; ?>
            <textarea id="<?= e($field) ?>" name="<?= e($field) ?>" rows="<?= $rows ?>"><?= e($val) ?></textarea>
            <?php if ($type === 'lines'): ?><small class="help">Jeden riadok = jedna položka.</small>
            <?php elseif ($type === 'pairs'): ?><small class="help">Jeden riadok = „Nadpis | text“ (zvislá čiara oddeľuje nadpis od textu).</small>
            <?php elseif ($type === 'rich'): ?><small class="help">Odseky oddeľte prázdnym riadkom. Nadpis začnite znakmi „## “. **tučné** a *kurzíva*.</small><?php endif; ?>
          <?php endif; ?>
          <?php if ($changed): ?><label class="reset-chk"><input type="checkbox" name="reset[<?= e($field) ?>]" value="1"> Vrátiť pôvodný text</label><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </details>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <div class="savebar">
      <button class="btn">Uložiť všetky texty</button>
      <span id="dirty" class="dirty" hidden>● Máte neuložené zmeny</span>
    </div>
    <p class="help">Značky v texte: <code>**tučné**</code>, <code>{name}</code> názov salónu, <code>{phone}</code> telefón, <code>{email}</code>, <code>{address}</code> – doplnia sa zo sekcie Nastavenia.</p>
  </form>
</div>
<script>
(function () {
  var q = document.getElementById('tSearch'), none = document.getElementById('tNone'), form = document.getElementById('textsForm');
  q.addEventListener('input', function () {
    var t = q.value.trim().toLowerCase(), any = false;
    document.querySelectorAll('.tsec').forEach(function (sec) {
      var vis = 0;
      sec.querySelectorAll('.tfield').forEach(function (f) {
        var ok = !t || f.dataset.search.indexOf(t) !== -1;
        f.style.display = ok ? '' : 'none'; if (ok) vis++;
      });
      sec.style.display = vis ? '' : 'none'; if (t && vis) sec.open = true; if (vis) any = true;
    });
    none.style.display = any ? 'none' : '';
  });
  var dirty = false, flag = document.getElementById('dirty');
  form.addEventListener('input', function () { dirty = true; flag.hidden = false; });
  form.addEventListener('submit', function () { dirty = false; });
  window.addEventListener('beforeunload', function (e) { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
})();
</script>
<?php admin_foot();
