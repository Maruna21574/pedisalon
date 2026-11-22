<?php
require __DIR__ . '/_boot.php';

$items = certificates_data();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $a = $_POST['action'] ?? 'save';
    if ($a === 'reset') {
        @unlink(CERTS_FILE);
        flash('Zoznam certifikátov bol obnovený na predvolený.');
        redirect('certifikaty.php');
    }
    $files = (array)($_POST['file'] ?? []);
    $titles = (array)($_POST['title'] ?? []);
    $dates = (array)($_POST['date'] ?? []);
    $orders = (array)($_POST['order'] ?? []);
    $dels = (array)($_POST['del'] ?? []);
    $out = [];
    foreach ($files as $i => $f) {
        if (!empty($dels[$i])) continue;
        $f = img_file((string)$f);
        $t = trim((string)($titles[$i] ?? ''));
        if ($f === '' || $t === '') continue;
        $out[] = ['file' => $f, 'title' => $t, 'date' => trim((string)($dates[$i] ?? '')), 'o' => (float)str_replace(',', '.', (string)($orders[$i] ?? $i + 1))];
    }
    $err = '';
    // Nový certifikát (nahratý obrázok)
    if (!empty($_FILES['new_img']['name'])) {
        $nt = trim((string)($_POST['new_title'] ?? ''));
        if ($nt === '') $err = 'Pri novom certifikáte zadajte názov.';
        else {
            [$path, $e] = save_upload($_FILES['new_img'], 'certifikat-' . $nt);
            if ($e) $err = $e;
            elseif ($path) $out[] = ['file' => $path, 'title' => $nt, 'date' => trim((string)($_POST['new_date'] ?? '')), 'o' => 9999];
        }
    }
    usort($out, fn($x, $y) => $x['o'] <=> $y['o']);
    $out = array_map(fn($r) => ['file' => $r['file'], 'title' => $r['title'], 'date' => $r['date']], $out);
    store_mutate(CERTS_FILE, fn() => ['data' => ['items' => $out]]);
    flash($err ? 'Uložené, ale: ' . $err : 'Certifikáty uložené.', $err ? 'bad' : 'ok');
    redirect('certifikaty.php');
}

admin_head('Certifikáty', 'certifikaty', '', 'Certifikáty a školenia zobrazené na stránke <b>O nás</b>. Zmeňte názov, dátum alebo poradie, certifikát odstráňte, alebo pridajte nový (nahrajte jeho fotku alebo sken). Nadpisy sekcie upravíte v Texty webu → O nás.');
?>
<div class="panel">
  <h2>Zoznam certifikátov (<?= count($items) ?>)</h2>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?><input type="hidden" name="action" value="save">
    <table class="tbl">
      <thead><tr><th>Náhľad</th><th>Názov</th><th>Dátum</th><th>Poradie</th><th>Zmazať</th></tr></thead>
      <tbody>
      <?php foreach ($items as $i => $c): ?>
        <tr>
          <td><img class="thumb-sm" src="../assets/img/<?= e($c['file']) ?>" alt="" style="object-fit:contain;background:#fff"><input type="hidden" name="file[<?= $i ?>]" value="<?= e($c['file']) ?>"></td>
          <td><input name="title[<?= $i ?>]" value="<?= e($c['title']) ?>" style="width:100%;min-width:220px"></td>
          <td><input name="date[<?= $i ?>]" value="<?= e($c['date']) ?>" style="width:8em" placeholder="napr. 5. 9. 2025"></td>
          <td><input type="number" step="1" name="order[<?= $i ?>]" value="<?= $i + 1 ?>" style="width:4.5em"></td>
          <td><label class="check" style="margin:0"><input type="checkbox" name="del[<?= $i ?>]" value="1"> <span>zmazať</span></label></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <p style="color:var(--muted);font-size:.9rem">Poradie: certifikáty sa zoradia podľa čísla (menšie je vyššie). Zmazaný certifikát zmizne len zo stránky, súbor s fotkou zostane na serveri.</p>

    <h3 style="margin-top:28px">Pridať nový certifikát</h3>
    <div class="grid-form">
      <div class="field"><label>Fotka alebo sken (JPG, PNG, WebP)</label><input type="file" name="new_img" accept="image/jpeg,image/png,image/webp"></div>
      <div class="field"><label>Názov</label><input name="new_title" placeholder="napr. Kurz gélových nechtov"></div>
      <div class="field"><label>Dátum</label><input name="new_date" placeholder="napr. 12. 3. 2026"></div>
    </div>
    <p style="color:var(--muted);font-size:.9rem">Tip: čím rovnejšie je certifikát odfotený (bez odleskov a šikmo), tým lepšie vyzerá. Obrázok sa automaticky zmenší.</p>
    <button class="btn">Uložiť certifikáty</button>
  </form>
</div>

<div class="panel hint">
  <form method="post" onsubmit="return confirm('Obnoviť zoznam certifikátov na predvolený? Vaše zmeny sa stratia.');">
    <?= csrf_field() ?><input type="hidden" name="action" value="reset">
    <p style="margin:0 0 10px">Ak ste si zoznam pokazili, môžete ho vrátiť na pôvodný (11 certifikátov).</p>
    <button class="btn btn-sm btn-ghost" type="submit">Obnoviť predvolený zoznam</button>
  </form>
</div>
<?php admin_foot();
