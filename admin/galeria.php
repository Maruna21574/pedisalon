<?php
require __DIR__ . '/_boot.php';

/** Aktuálne dáta galérie (predvolené, kým sa prvýkrát neuložia). */
function gallery_base(array $stored): array { return isset($stored['items'], $stored['cats']) ? $stored : gallery_data(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $a = $_POST['action'] ?? '';
    $msg = ''; $type = 'ok';

    if ($a === 'upload') {
        $cat = (string)($_POST['cat'] ?? '');
        $inGallery = !empty($_POST['in_gallery']);
        $home = !empty($_POST['home']);
        $label = trim((string)($_POST['label'] ?? ''));
        $added = []; $errors = [];
        foreach ($_FILES['photos']['name'] ?? [] as $i => $_) {
            [$path, $err] = save_upload(['name' => $_FILES['photos']['name'][$i], 'tmp_name' => $_FILES['photos']['tmp_name'][$i], 'error' => $_FILES['photos']['error'][$i], 'size' => $_FILES['photos']['size'][$i]], $label);
            if ($err) $errors[] = $err; elseif ($path) $added[] = $path;
        }
        if ($added && $inGallery) {
            store_mutate(GALLERY_FILE, function (array $old) use ($added, $cat, $label, $home) {
                $g = gallery_base($old);
                if (!isset($g['cats'][$cat])) $cat = (string)array_key_first($g['cats']);
                foreach ($added as $f) $g['items'][] = ['file' => $f, 'cat' => $cat, 'label' => $label !== '' ? $label : ($g['cats'][$cat] ?? ''), 'home' => $home];
                return ['data' => $g];
            });
        }
        $msg = $added ? count($added) . ' fotiek nahraných' . ($inGallery ? ' a pridaných do galérie.' : ' do knižnice.') : 'Nenahrala sa žiadna fotka.';
        if ($errors) { $msg .= ' ' . implode(' ', array_unique($errors)); $type = $added ? 'ok' : 'bad'; }
        elseif (!$added) $type = 'bad';
    } elseif ($a === 'save') {
        $before = gallery_base(store_read(GALLERY_FILE));
        $items = []; $orders = []; $removed = [];
        foreach ($_POST['it'] ?? [] as $i => $in) {
            $file = img_file((string)($in['file'] ?? ''));
            if (!empty($in['delete'])) { $removed[] = $file; continue; }
            $cat = (string)($in['cat'] ?? '');
            if (!isset($before['cats'][$cat])) $cat = (string)array_key_first($before['cats']);
            $items[] = ['file' => $file, 'cat' => $cat, 'label' => trim((string)($in['label'] ?? '')) ?: $before['cats'][$cat], 'home' => !empty($in['home'])];
            $orders[] = (int)($in['order'] ?? 0);
        }
        array_multisort($orders, SORT_ASC, $items);
        store_mutate(GALLERY_FILE, fn(array $old) => ['data' => ['cats' => gallery_base($old)['cats'], 'items' => $items]]);
        // Zmazané nahrané súbory odstránime aj z disku, ak sa už nikde nepoužívajú
        foreach ($removed as $f) {
            if (preg_match('#^uploads/[a-z0-9-]+\.jpg$#', $f) && !media_in_use($f)) delete_upload($f);
        }
        $msg = 'Galéria uložená.';
    } elseif ($a === 'add_cat') {
        $name = trim((string)($_POST['cat_name'] ?? ''));
        if ($name === '') { $msg = 'Zadajte názov kategórie.'; $type = 'bad'; }
        else {
            store_mutate(GALLERY_FILE, function (array $old) use ($name) {
                $g = gallery_base($old); $slug = slugify($name);
                for ($i = 2; isset($g['cats'][$slug]); $i++) $slug = slugify($name) . '-' . $i;
                $g['cats'][$slug] = $name;
                return ['data' => $g];
            });
            $msg = 'Kategória pridaná.';
        }
    } elseif ($a === 'del_cat') {
        $slug = (string)($_POST['slug'] ?? '');
        $g = gallery_base(store_read(GALLERY_FILE));
        if (in_array($slug, array_column($g['items'], 'cat'), true)) { $msg = 'Kategória obsahuje fotky – najprv ich presuňte inam.'; $type = 'bad'; }
        elseif (count($g['cats']) <= 1) { $msg = 'Musí zostať aspoň jedna kategória.'; $type = 'bad'; }
        else {
            unset($g['cats'][$slug]);
            store_mutate(GALLERY_FILE, fn(array $old) => ['data' => $g]);
            $msg = 'Kategória zmazaná.';
        }
    } elseif ($a === 'delete_media') {
        $f = (string)($_POST['file'] ?? '');
        if (!preg_match('#^uploads/[a-z0-9-]+\.jpg$#', $f)) { $msg = 'Neplatný súbor.'; $type = 'bad'; }
        elseif (media_in_use($f)) { $msg = 'Fotka sa ešte používa (galéria, služba alebo text).'; $type = 'bad'; }
        else { delete_upload($f); $msg = 'Fotka zmazaná.'; }
    }
    if ($msg) flash($msg, $type);
    redirect('galeria.php');
}

$G = gallery_data();
$uploads = array_values(array_filter(media_list(), fn($f) => str_starts_with($f, 'uploads/')));
admin_head('Galéria a fotky', 'galeria');
?>
<div class="panel">
  <h2>Nahrať fotky</h2>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?><input type="hidden" name="action" value="upload">
    <div class="grid-form">
      <div class="field"><label>Fotky (môžete vybrať viac naraz)</label><input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" required></div>
      <div class="field"><label>Kategória</label><select name="cat"><?php foreach ($G['cats'] as $slug => $name): ?><option value="<?= e($slug) ?>"><?= e($name) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label>Popis fotky (voliteľné)</label><input name="label" maxlength="80"></div>
    </div>
    <label class="check"><input type="checkbox" name="in_gallery" value="1" checked> <span>Pridať do galérie</span></label>
    <label class="check"><input type="checkbox" name="home" value="1"> <span>Zobraziť aj v ukážkach na úvodnej stránke</span></label>
    <p style="color:var(--muted);font-size:.9rem">Fotky sa automaticky zmenšia (max. 1600 px) a uložia v kvalite vhodnej pre web. Povolené: JPG, PNG, WebP do 15 MB. Bez zaškrtnutia „Pridať do galérie“ sa fotka uloží len do knižnice a použijete ju pri službách či textoch.</p>
    <button class="btn btn-sm">Nahrať</button>
  </form>
</div>

<div class="panel">
  <h2>Kategórie galérie</h2>
  <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">
    <?php foreach ($G['cats'] as $slug => $name): ?>
      <form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="action" value="del_cat"><input type="hidden" name="slug" value="<?= e($slug) ?>">
        <span class="pill confirmed"><?= e($name) ?> <button class="mini" style="padding:.1em .5em" title="Zmazať kategóriu" data-confirm="Zmazať kategóriu „<?= e($name) ?>“?">×</button></span></form>
    <?php endforeach; ?>
  </div>
  <form method="post" style="display:flex;gap:12px;flex-wrap:wrap;align-items:end">
    <?= csrf_field() ?><input type="hidden" name="action" value="add_cat">
    <div class="field" style="margin:0"><label>Nová kategória</label><input name="cat_name" maxlength="40" required></div>
    <button class="btn btn-sm">Pridať kategóriu</button>
  </form>
</div>

<form method="post" class="panel">
  <?= csrf_field() ?><input type="hidden" name="action" value="save">
  <h2>Fotky v galérii (<?= count($G['items']) ?>)</h2>
  <p style="color:var(--muted);margin-top:0">Poradie určuje číslo (menšie = skôr). Políčko „Úvodná“ zobrazí fotku v pásiku ukážok na úvodnej stránke.</p>
  <table class="tbl gal-tbl">
    <thead><tr><th>Fotka</th><th>Kategória</th><th>Popis</th><th>Úvodná</th><th>Poradie</th><th>Zmazať</th></tr></thead>
    <tbody>
    <?php foreach ($G['items'] as $i => $it): ?>
      <tr>
        <td><input type="hidden" name="it[<?= $i ?>][file]" value="<?= e($it['file']) ?>"><img class="thumb-sm" src="../assets/img/<?= e(img_file($it['file'])) ?>" alt="" loading="lazy"><br><small style="color:var(--muted)"><?= e(basename($it['file'])) ?></small></td>
        <td><select name="it[<?= $i ?>][cat]"><?php foreach ($G['cats'] as $slug => $name): ?><option value="<?= e($slug) ?>" <?= $it['cat'] === $slug ? 'selected' : '' ?>><?= e($name) ?></option><?php endforeach; ?></select></td>
        <td><input name="it[<?= $i ?>][label]" value="<?= e($it['label']) ?>" maxlength="80"></td>
        <td style="text-align:center"><input type="checkbox" name="it[<?= $i ?>][home]" value="1" <?= !empty($it['home']) ? 'checked' : '' ?>></td>
        <td><input type="number" name="it[<?= $i ?>][order]" value="<?= ($i + 1) * 10 ?>" style="width:5.5em"></td>
        <td style="text-align:center"><input type="checkbox" name="it[<?= $i ?>][delete]" value="1"></td>
      </tr>
    <?php endforeach; if (!$G['items']): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px">Galéria je prázdna – nahrajte fotky vyššie.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
  <div class="savebar" style="position:static;margin-top:18px"><button class="btn">Uložiť galériu</button></div>
</form>

<div class="panel">
  <h2>Knižnica nahraných fotiek (<?= count($uploads) ?>)</h2>
  <p style="color:var(--muted);margin-top:0">Fotky, ktoré ste nahrali. Zmazať ich možno, len ak sa nikde nepoužívajú.</p>
  <div class="media-grid">
    <?php foreach ($uploads as $f): $used = media_in_use($f); ?>
    <div class="media-item static">
      <img src="../assets/img/<?= e($f) ?>" alt="" loading="lazy"><span><?= e(basename($f)) ?></span>
      <?php if ($used): ?><small style="color:var(--sage-600)">používa sa</small>
      <?php else: ?><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete_media"><input type="hidden" name="file" value="<?= e($f) ?>"><button class="mini del" data-confirm="Natrvalo zmazať fotku?">Zmazať</button></form><?php endif; ?>
    </div>
    <?php endforeach; if (!$uploads): ?><p style="color:var(--muted);margin:0">Zatiaľ ste nenahrali žiadne fotky.</p><?php endif; ?>
  </div>
</div>
<?php admin_foot();
