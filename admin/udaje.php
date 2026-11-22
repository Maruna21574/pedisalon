<?php
require __DIR__ . '/_boot.php';

/** Zhoda záznamu s hľadanou osobou: e-mail (presne), telefón (rovnaké číslice) alebo meno (presne, bez rozlišovania veľkosti). */
function person_match(array $r, string $q): bool {
    $q = trim($q);
    if ($q === '') return false;
    if (str_contains($q, '@')) return mb_strtolower(trim((string)($r['email'] ?? ''))) === mb_strtolower($q);
    $digits = preg_replace('/\D/', '', $q);
    if (strlen($digits) >= 7) { $p = preg_replace('/\D/', '', (string)($r['phone'] ?? '')); return $p !== '' && substr($p, -9) === substr($digits, -9); }
    return mb_strtolower(trim((string)($r['name'] ?? ''))) === mb_strtolower($q);
}

$q = trim((string)($_GET['q'] ?? $_POST['q'] ?? ''));
$bookings = store_read(BOOKINGS_FILE);
$messages = is_file(DATA_DIR . '/messages.json') ? store_read(DATA_DIR . '/messages.json') : [];
$foundB = array_values(array_filter($bookings, fn($r) => person_match($r, $q)));
$foundM = array_values(array_filter($messages, fn($r) => person_match($r, $q)));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $a = $_POST['action'] ?? '';
    if ($a === 'export' && $q !== '') {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="udaje-klienta-' . date('Y-m-d') . '.json"');
        echo json_encode(['hľadané' => $q, 'vytvorené' => date('c'), 'rezervácie' => $foundB, 'správy' => $foundM], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    if ($a === 'erase' && $q !== '') {
        $nb = count($foundB); $nm = count($foundM);
        store_mutate(BOOKINGS_FILE, fn(array $all) => ['data' => array_values(array_filter($all, fn($r) => !person_match($r, $q)))]);
        if (is_file(DATA_DIR . '/messages.json')) store_mutate(DATA_DIR . '/messages.json', fn(array $all) => ['data' => array_values(array_filter($all, fn($r) => !person_match($r, $q)))]);
        flash("Údaje boli vymazané (rezervácie: $nb, správy: $nm).");
        redirect('udaje.php');
    }
    if ($a === 'backup') {
        $zip = new ZipArchive();
        $tmp = tempnam(sys_get_temp_dir(), 'pzb');
        if ($zip->open($tmp, ZipArchive::OVERWRITE) !== true) { flash('Zálohu sa nepodarilo vytvoriť.', 'bad'); redirect('udaje.php'); }
        foreach (glob(DATA_DIR . '/*.json') ?: [] as $f) $zip->addFile($f, 'data/' . basename($f));
        if (!empty($_POST['with_images']) && is_dir(UPLOAD_DIR)) foreach (glob(UPLOAD_DIR . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [] as $f) $zip->addFile($f, 'uploads/' . basename($f));
        $zip->close();
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="pedisalon-zaloha-' . date('Y-m-d') . '.zip"');
        header('Content-Length: ' . filesize($tmp));
        readfile($tmp); @unlink($tmp);
        exit;
    }
    redirect('udaje.php');
}

admin_head('Údaje a zálohy', 'udaje', '', 'Vybavenie žiadostí klientov o ich údaje (GDPR) a záloha údajov webu.');
$fmt = fn($r) => e(($r['date'] ?? '') . ' ' . ($r['start'] ?? ''));
?>
<div class="panel">
  <h2>Údaje klienta (GDPR)</h2>
  <p style="color:var(--muted)">Klient má právo vedieť, aké jeho údaje o ňom máme, a požiadať o ich vymazanie. Zadajte jeho <b>e-mail</b>, <b>telefón</b> alebo <b>celé meno</b> (presne tak, ako sa zadal pri rezervácii). Systém nájde jeho rezervácie a správy z formulára.</p>
  <form method="get" class="grid-form" style="align-items:end">
    <div class="field"><label>E-mail, telefón alebo celé meno</label><input name="q" value="<?= e($q) ?>" placeholder="napr. anna@example.com"></div>
    <div class="field"><button class="btn">Vyhľadať</button></div>
  </form>

  <?php if ($q !== ''): ?>
    <h3 style="margin-top:24px">Nájdené záznamy: rezervácie <?= count($foundB) ?>, správy <?= count($foundM) ?></h3>
    <?php if ($foundB): ?>
    <table class="tbl"><thead><tr><th>Termín</th><th>Služba</th><th>Meno</th><th>Telefón</th><th>E-mail</th><th>Poznámka</th></tr></thead><tbody>
      <?php foreach ($foundB as $r): ?><tr><td><?= $fmt($r) ?></td><td><?= e($r['service_name'] ?? '') ?></td><td><?= e($r['name'] ?? '') ?></td><td><?= e($r['phone'] ?? '') ?></td><td><?= e($r['email'] ?? '') ?></td><td><?= e($r['note'] ?? '') ?></td></tr><?php endforeach; ?>
    </tbody></table>
    <?php endif; ?>
    <?php if ($foundM): ?>
    <table class="tbl"><thead><tr><th>Dátum</th><th>Meno</th><th>E-mail</th><th>Správa</th></tr></thead><tbody>
      <?php foreach ($foundM as $r): ?><tr><td><?= e(substr((string)($r['date'] ?? ''), 0, 10)) ?></td><td><?= e($r['name'] ?? '') ?></td><td><?= e($r['email'] ?? '') ?></td><td><?= e(mb_strimwidth((string)($r['message'] ?? ''), 0, 120, '…')) ?></td></tr><?php endforeach; ?>
    </tbody></table>
    <?php endif; ?>
    <?php if ($foundB || $foundM): ?>
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:14px">
      <form method="post"><?= csrf_field() ?><input type="hidden" name="q" value="<?= e($q) ?>"><input type="hidden" name="action" value="export"><button class="btn btn-ghost">Stiahnuť údaje (JSON)</button></form>
      <form method="post"><?= csrf_field() ?><input type="hidden" name="q" value="<?= e($q) ?>"><input type="hidden" name="action" value="erase"><button class="btn" style="background:#b23a3a" data-confirm="Naozaj natrvalo vymazať všetky tieto záznamy? Akcia sa nedá vrátiť.">Vymazať všetky údaje klienta</button></form>
    </div>
    <p style="color:var(--muted);font-size:.9rem;margin-top:10px">E-maily, ktoré ste klientovi poslali, a doklady vo vašej účtovnej evidencii touto akciou nezmažete – tie riešte samostatne.</p>
    <?php else: ?><p>Pre túto osobu sme nenašli žiadne záznamy.</p><?php endif; ?>
  <?php endif; ?>
</div>

<div class="panel">
  <h2>Záloha údajov</h2>
  <p style="color:var(--muted)">Stiahne súbor ZIP s rezerváciami, nastaveniami, textami, službami, galériou, recenziami, certifikátmi a témami Poradne. Odporúčame ho robiť pravidelne (napríklad raz mesačne) a uložiť mimo servera.</p>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="backup">
    <label class="check"><input type="checkbox" name="with_images" value="1"> <span>Pridať aj fotky nahrané cez administráciu (súbor bude väčší)</span></label>
    <button class="btn">Stiahnuť zálohu</button>
  </form>
</div>

<div class="panel hint">
  <p style="margin:0"><b>Automatické mazanie:</b> rezervácie a správy z formulára sa po 12 mesiacoch od termínu mažú samy (podľa zásad ochrany osobných údajov).</p>
</div>
<?php admin_foot();
