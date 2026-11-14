<?php
require __DIR__ . '/_boot.php';

$isNew = isset($_GET['new']) || (($_POST['id'] ?? '') === '' && isset($_POST['is_new']));
$id = (string)($_GET['id'] ?? $_POST['id'] ?? '');
if (!$isNew && !isset($C['services'][$id])) { flash('Služba neexistuje.', 'bad'); redirect('sluzby.php'); }
$svc = $isNew ? ['name' => '', 'short' => '', 'duration' => 45, 'price' => 20, 'menu' => '', 'img' => media_list()[0] ?? '', 'icon' => 'foot'] : $C['services'][$id];
$pageKey = $isNew ? '' : page_key($id, $svc);
$url = $isNew ? 'sluzba-edit.php?new=1' : 'sluzba-edit.php?id=' . urlencode($id);

/** Prečíta a overí údaje služby. Vráti [služba, chyba]. */
function read_service(array $in, array $base, array $upload): array {
    $name = trim((string)($in['name'] ?? ''));
    if ($name === '') return [null, 'Služba musí mať názov.'];
    $dur = (int)($in['duration'] ?? 0);
    if ($dur < 5 || $dur > 480) return [null, 'Trvanie služby musí byť 5 – 480 minút.'];
    $price = (float)str_replace(',', '.', (string)($in['price'] ?? ''));
    if ($price < 0) return [null, 'Cena nemôže byť záporná.'];
    $img = img_file((string)($in['img'] ?? ''));
    [$up, $err] = save_upload($upload, $name);
    if ($err) return [null, $err];
    if ($up) $img = $up;
    elseif (!in_array($img, media_list(), true)) $img = $base['img'] ?? (media_list()[0] ?? '');
    $icon = isset(ICONS[$in['icon'] ?? '']) ? $in['icon'] : ($base['icon'] ?? 'foot');
    // $base nesie nezmenené polia (napr. 'page'), ktoré formulár needituje
    return [array_merge($base, [
        'name' => $name, 'short' => trim((string)($in['short'] ?? '')), 'duration' => $dur, 'price' => $price,
        'menu' => trim((string)($in['menu'] ?? '')) ?: $name, 'img' => $img, 'icon' => $icon,
    ]), ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $a = $_POST['action'] ?? 'save';

    if ($a === 'delete' && !$isNew) {
        if (count($C['services']) <= 1) { flash('Musí zostať aspoň jedna služba.', 'bad'); redirect($url); }
        $services = $C['services']; unset($services[$id]);
        save_services($services);
        if (str_starts_with($pageKey, 'svc:')) store_mutate(PAGES_FILE, function (array $all) use ($pageKey) { unset($all[$pageKey]); return ['data' => $all]; });
        flash('Služba „' . $svc['name'] . '“ bola zmazaná.');
        redirect('sluzby.php');
    }
    if ($a === 'reset_page' && !$isNew && !str_starts_with($pageKey, 'svc:')) {
        store_mutate(PAGES_FILE, function (array $all) use ($pageKey) { unset($all[$pageKey]); return ['data' => $all]; });
        flash('Texty a fotky podstránky boli vrátené na predvolené.');
        redirect($url);
    }

    // --- Uloženie služby ---
    [$new, $error] = read_service($_POST['s'] ?? [], $isNew ? [] : $svc, $_FILES['img_up'] ?? ['error' => UPLOAD_ERR_NO_FILE]);
    if ($error) { flash($error, 'bad'); redirect($url); }
    $services = $C['services'];
    if ($isNew) {
        $id = slugify($new['name']);
        for ($i = 2; isset($services[$id]); $i++) $id = slugify($new['name']) . '-' . $i;
        $new['page'] = 'sluzba.php?id=' . $id;
        $pageKey = 'svc:' . $id;
    }
    $services[$id] = $new;
    save_services($services);

    // --- Uloženie podstránky ---
    $text = fn(string $f) => trim((string)($_POST['p'][$f] ?? ''));
    $photos = array_values(array_filter((array)($_POST['photos'] ?? []), fn($p) => is_string($p) && in_array($p, media_list(), true)));
    $errors = [];
    foreach ($_FILES['up']['name'] ?? [] as $i => $_) {
        [$path, $err] = save_upload(['name' => $_FILES['up']['name'][$i], 'tmp_name' => $_FILES['up']['tmp_name'][$i], 'error' => $_FILES['up']['error'][$i], 'size' => $_FILES['up']['size'][$i]], $new['name']);
        if ($err) $errors[] = $err; elseif ($path) $photos[] = $path;
    }
    $data = [
        'title'    => $text('title') ?: $new['name'],
        'h1'       => $text('h1') ?: $new['name'],
        'lead'     => $text('lead'),
        'desc'     => $text('desc'),
        'intro'    => array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/u', $text('intro'))), fn($x) => $x !== '')),
        'note'     => $text('note'),
        'includes' => lines($text('includes')),
        'steps'    => pairs($text('steps')),
        'tips'     => lines($text('tips')),
        'faq'      => pairs($text('faq')),
        'photos'   => $photos,
    ];
    store_mutate(PAGES_FILE, function (array $all) use ($pageKey, $data) { $all[$pageKey] = $data; return ['data' => $all]; });

    flash($errors ? 'Uložené, ale: ' . implode(' ', $errors) : ($isNew ? 'Služba bola pridaná.' : 'Služba uložená.'), $errors ? 'bad' : 'ok');
    redirect('sluzba-edit.php?id=' . urlencode($id));
}

// --- Zobrazenie ---
$P = $isNew ? ['title' => '', 'h1' => '', 'lead' => '', 'desc' => '', 'intro' => [], 'note' => '', 'includes' => [], 'steps' => [], 'tips' => [], 'faq' => [], 'photos' => []] : page_content($pageKey);
$all = media_list();
$selected = array_values(array_filter(array_map('img_file', $P['photos']), fn($f) => in_array($f, $all, true)));
$ordered = array_merge($selected, array_values(array_diff($all, $selected)));
$sharedWith = [];
if (!$isNew) foreach (service_pages()[$pageKey]['services'] ?? [] as $other) if ($other !== $id) $sharedWith[] = $C['services'][$other]['name'];

admin_head($isNew ? 'Nová služba' : 'Služba: ' . $svc['name'], 'sluzby', '', $isNew ? 'Vyplňte údaje a texty. Služba dostane vlastnú podstránku a objaví sa v menu, cenníku aj v rezervácii.' : '<a href="sluzby.php">← Späť na zoznam služieb</a> · <a href="../' . e($svc['page']) . '" target="_blank">Zobraziť podstránku na webe</a>');
?>
<nav class="subnav">
  <a href="#zaklad">1 · Základné údaje</a><a href="#hlavicka">2 · Hlavička podstránky</a><a href="#texty">3 · Texty podstránky</a><a href="#fotky">4 · Fotky</a><?php if (!$isNew): ?><a href="#zmazat" class="danger">Zmazať službu</a><?php endif; ?>
</nav>

<form method="post" enctype="multipart/form-data" id="svcForm">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= e($id) ?>"><?php if ($isNew): ?><input type="hidden" name="is_new" value="1"><?php endif; ?>

  <div class="panel" id="zaklad">
    <h2>1 · Základné údaje <small class="where">cenník · karty služieb · rezervácia</small></h2>
    <div class="grid-form">
      <div class="field"><label>Názov služby</label><input name="s[name]" value="<?= e($svc['name']) ?>" required></div>
      <div class="field"><label>Názov v menu a pätičke</label><input name="s[menu]" value="<?= e($svc['menu'] ?? $svc['name']) ?>"><small class="help">Kratší názov. Prázdne = celý názov.</small></div>
      <div class="field"><label>Cena od (€)</label><input name="s[price]" value="<?= e(rtrim(rtrim(number_format((float)$svc['price'], 2, ',', ''), '0'), ',')) ?>" inputmode="decimal" required></div>
      <div class="field"><label>Trvanie (minúty)</label><input type="number" name="s[duration]" value="<?= (int)$svc['duration'] ?>" min="5" max="480" step="5" required><small class="help">Podľa trvania sa ponúkajú voľné termíny.</small></div>
      <div class="field"><label>Ikona na karte</label><select name="s[icon]"><?php foreach (ICONS as $ik => $il): ?><option value="<?= $ik ?>" <?= ($svc['icon'] ?? '') === $ik ? 'selected' : '' ?>><?= e($il) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="field"><label>Krátky popis (na kartách a v cenníku)</label><textarea name="s[short]" rows="2"><?= e($svc['short']) ?></textarea></div>
    <div class="grid-form">
      <div class="field"><label>Fotka služby (karta, rezervácia)</label><?= image_picker('s[img]', $svc['img'], 'pv_svc') ?></div>
      <div class="field"><label>…alebo nahrať novú fotku</label><input type="file" name="img_up" accept="image/jpeg,image/png,image/webp"></div>
    </div>
  </div>

  <?php if ($sharedWith): ?>
  <div class="alert bad" style="background:#fff7e0;border-color:#f0d58a;color:#7a5a00">Pozor: podstránka (časti 2–4) je <b>spoločná</b> aj pre službu <?= e(implode(', ', $sharedWith)) ?>. Zmeny sa prejavia pri všetkých.</div>
  <?php endif; ?>

  <div class="panel" id="hlavicka">
    <h2>2 · Hlavička podstránky <small class="where">horná časť stránky služby</small></h2>
    <div class="grid-form">
      <div class="field"><label>Hlavný nadpis (H1)</label><input name="p[h1]" value="<?= e($P['h1']) ?>"></div>
      <div class="field"><label>Titulok v karte prehliadača</label><input name="p[title]" value="<?= e($P['title']) ?>"></div>
    </div>
    <div class="field"><label>Úvodná veta pod nadpisom</label><textarea name="p[lead]" rows="2"><?= e($P['lead']) ?></textarea></div>
    <div class="field"><label>Popis pre vyhľadávače (Google)</label><textarea name="p[desc]" rows="2"><?= e($P['desc']) ?></textarea></div>
  </div>

  <div class="panel" id="texty">
    <h2>3 · Texty podstránky <small class="where">hlavný obsah stránky služby</small></h2>
    <div class="field"><label>O službe (odseky oddeľte prázdnym riadkom)</label><textarea name="p[intro]" rows="7"><?= e(implode("\n\n", $P['intro'])) ?></textarea></div>
    <div class="field"><label>Zvýraznená poznámka (voliteľné)</label><textarea name="p[note]" rows="2"><?= e($P['note'] ?? '') ?></textarea></div>
    <div class="field"><label>Čo služba zahŕňa</label><textarea name="p[includes]" rows="6"><?= e(implode("\n", $P['includes'])) ?></textarea><small class="help">Jeden riadok = jedna odrážka.</small></div>
    <div class="field"><label>Ako to prebieha</label><textarea name="p[steps]" rows="5"><?= e(implode("\n", array_map(fn($p) => $p[0] . ' | ' . $p[1], $P['steps']))) ?></textarea><small class="help">Jeden riadok = „Nadpis kroku | popis“.</small></div>
    <div class="field"><label>Tipy pre klienta</label><textarea name="p[tips]" rows="4"><?= e(implode("\n", $P['tips'])) ?></textarea><small class="help">Jeden riadok = jeden tip.</small></div>
    <div class="field"><label>Časté otázky</label><textarea name="p[faq]" rows="6"><?= e(implode("\n", array_map(fn($p) => $p[0] . ' | ' . $p[1], $P['faq']))) ?></textarea><small class="help">Jeden riadok = „Otázka | odpoveď“. Nadpisy sekcií („O službe“, „Čo zahŕňa“…) upravíte v <a href="texty.php">Texty webu</a>.</small></div>
  </div>

  <div class="panel" id="fotky">
    <h2>4 · Fotky na podstránke <small class="where">pásik fotiek pod odrážkami</small></h2>
    <p style="color:var(--muted);margin-top:0">Zaškrtnuté fotky sa zobrazia na stránke (zaškrtnuté sú hore v poradí, v akom sa zobrazujú).</p>
    <div class="media-grid">
      <?php foreach ($ordered as $f): ?>
      <label class="media-item <?= in_array($f, $selected, true) ? 'on' : '' ?>">
        <input type="checkbox" name="photos[]" value="<?= e($f) ?>" <?= in_array($f, $selected, true) ? 'checked' : '' ?>>
        <img src="../assets/img/<?= e($f) ?>" alt="" loading="lazy"><span><?= e(basename($f)) ?></span>
      </label>
      <?php endforeach; ?>
    </div>
    <div class="field" style="margin-top:18px"><label>Nahrať nové fotky a pridať ich sem</label><input type="file" name="up[]" multiple accept="image/jpeg,image/png,image/webp"></div>
  </div>

  <div class="savebar">
    <button class="btn"><?= $isNew ? 'Pridať službu' : 'Uložiť všetko' ?></button>
    <a class="btn btn-ghost" href="sluzby.php">Zrušiť</a>
    <?php if (!$isNew && !str_starts_with($pageKey, 'svc:')): ?>
    <button class="btn btn-ghost" name="action" value="reset_page" formnovalidate data-confirm="Vrátiť texty a fotky podstránky na pôvodné?">Obnoviť predvolené texty podstránky</button>
    <?php endif; ?>
  </div>
</form>

<?php if (!$isNew): ?>
<div class="panel danger-zone" id="zmazat">
  <h2>Zmazať službu</h2>
  <p style="color:var(--muted)">Služba zmizne z webu, cenníka aj rezervácie. Už vytvorené rezervácie zostanú zachované. Krok nie je možné vrátiť späť.</p>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($id) ?>"><input type="hidden" name="action" value="delete">
    <button class="btn btn-sm" style="background:var(--rose-700)" data-confirm="Naozaj zmazať službu „<?= e($svc['name']) ?>“?">Zmazať túto službu</button></form>
</div>
<?php endif; ?>
<?php admin_foot();
