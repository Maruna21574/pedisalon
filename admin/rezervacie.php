<?php
require __DIR__ . '/_boot.php';

/* ---- Akcie ---- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $a = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? '';
    $msg = '';
    if (in_array($a, ['confirmed', 'cancelled', 'pending'], true)) {
        store_mutate(BOOKINGS_FILE, function (array $all) use ($id, $a) {
            foreach ($all as &$b) if ($b['id'] === $id) $b['status'] = $a;
            return ['data' => $all];
        });
        if ($a !== 'pending') {
            // Informuj klienta e-mailom
            foreach (store_read(BOOKINGS_FILE) as $b) if ($b['id'] === $id && !empty($b['email'])) {
                $when = sk_date($b['date']) . ' o ' . $b['start'];
                $txt = mail_text($a === 'confirmed' ? 'mail.confirmed' : 'mail.cancelled', ['{client}' => $b['name'], '{service}' => $b['service_name'], '{when}' => $when]);
                send_mail($b['email'], 'Rezervácia – ' . ($a === 'confirmed' ? 'potvrdenie' : 'zrušenie'), $txt, $C['salon']['email']);
            }
        }
        $msg = 'Rezervácia aktualizovaná.';
    } elseif ($a === 'delete') {
        store_mutate(BOOKINGS_FILE, fn(array $all) => ['data' => array_values(array_filter($all, fn($b) => $b['id'] !== $id))]);
        $msg = 'Rezervácia zmazaná.';
    } elseif ($a === 'close_day' && is_valid_date($_POST['date'] ?? '')) {
        $d = $_POST['date'];
        store_mutate(CLOSED_FILE, function (array $all) use ($d) { if (!in_array($d, $all, true)) $all[] = $d; sort($all); return ['data' => $all]; });
        $msg = 'Deň ' . sk_date($d) . ' je uzavretý pre rezervácie.';
    } elseif ($a === 'open_day') {
        $d = $_POST['date'] ?? '';
        store_mutate(CLOSED_FILE, fn(array $all) => ['data' => array_values(array_filter($all, fn($x) => $x !== $d))]);
        $msg = 'Deň opäť otvorený.';
    } elseif ($a === 'manual' && is_valid_date($_POST['date'] ?? '') && isset($C['services'][$_POST['service'] ?? ''])) {
        // Ručne pridaná rezervácia (napr. telefonická)
        $svc = $C['services'][$_POST['service']];
        $time = $_POST['time'] ?? '';
        $res = preg_match('/^\d{2}:\d{2}$/', $time) ? store_mutate(BOOKINGS_FILE, function (array $all) use ($svc, $time) {
            $all[] = ['id' => bin2hex(random_bytes(6)), 'service' => $_POST['service'], 'service_name' => $svc['name'], 'duration' => (int)$svc['duration'], 'price' => $svc['price'],
                'date' => $_POST['date'], 'start' => $time, 'name' => trim($_POST['name'] ?? '') ?: 'Telefonická rezervácia', 'phone' => trim($_POST['phone'] ?? ''), 'email' => '', 'note' => 'Pridané ručne',
                'status' => 'confirmed', 'created' => date('c')];
            return ['data' => $all, 'return' => true];
        }) : false;
        $msg = $res ? 'Rezervácia pridaná.' : 'Neplatné údaje.';
    }
    if ($msg) flash($msg);
    redirect('rezervacie.php' . (isset($_GET['view']) ? '?view=' . urlencode($_GET['view']) : ''));
}

$view = $_GET['view'] ?? 'upcoming';
$all = store_read(BOOKINGS_FILE);
$closed = store_read(CLOSED_FILE);
$today = date('Y-m-d');
$rows = array_filter($all, fn($b) => $view === 'all' || ($view === 'pending' ? ($b['status'] === 'pending') : $b['date'] >= $today));
usort($rows, fn($x, $y) => [$x['date'], $x['start']] <=> [$y['date'], $y['start']]);
$pendingCount = count(array_filter($all, fn($b) => $b['status'] === 'pending' && $b['date'] >= $today));
$labels = ['pending' => 'Čaká', 'confirmed' => 'Potvrdená', 'cancelled' => 'Zrušená'];

admin_head('Rezervácie', 'rezervacie', $pendingCount ? '<span class="pill pending">' . $pendingCount . ' čaká</span>' : '');
?>
  <div class="panel">
    <div class="filters" style="justify-content:flex-start;margin:0 0 16px">
      <?php foreach (['upcoming' => 'Nadchádzajúce', 'pending' => 'Čakajúce na potvrdenie', 'all' => 'Všetky'] as $k => $l): ?>
        <a href="?view=<?= $k ?>"><button class="<?= $view === $k ? 'on' : '' ?>" type="button"><?= $l ?></button></a>
      <?php endforeach; ?>
    </div>
    <table class="tbl">
      <thead><tr><th>Termín</th><th>Služba</th><th>Klient</th><th>Stav</th><th>Akcie</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $b): ?>
        <tr>
          <td><b><?= e(date('j. n. Y', strtotime($b['date']))) ?></b><br><?= e($b['start']) ?> – <?= e(min_to_hm(hm_to_min($b['start']) + (int)$b['duration'])) ?></td>
          <td><?= e($b['service_name']) ?></td>
          <td><b><?= e($b['name']) ?></b><br><a href="tel:<?= e($b['phone']) ?>"><?= e($b['phone']) ?></a><?= $b['email'] ? '<br><a href="mailto:' . e($b['email']) . '">' . e($b['email']) . '</a>' : '' ?><?= !empty($b['note']) ? '<br><small style="color:var(--muted)">' . e($b['note']) . '</small>' : '' ?></td>
          <td><span class="pill <?= e($b['status']) ?>"><?= e($labels[$b['status']] ?? $b['status']) ?></span></td>
          <td>
            <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($b['id']) ?>">
              <?php if ($b['status'] !== 'confirmed'): ?><button class="mini ok" name="action" value="confirmed">Potvrdiť</button><?php endif; ?>
              <?php if ($b['status'] !== 'cancelled'): ?><button class="mini no" name="action" value="cancelled" data-confirm="Zrušiť rezerváciu a informovať klienta?">Zrušiť</button><?php endif; ?>
              <button class="mini del" name="action" value="delete" data-confirm="Natrvalo zmazať?">Zmazať</button>
            </form>
          </td>
        </tr>
      <?php endforeach; if (!$rows): ?>
        <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px">Žiadne rezervácie.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="panel">
    <h2>Pridať rezerváciu ručne (telefonická)</h2>
    <form method="post" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;align-items:end">
      <?= csrf_field() ?><input type="hidden" name="action" value="manual">
      <div class="field" style="margin:0"><label>Služba</label><select name="service"><?php foreach ($C['services'] as $id => $s): ?><option value="<?= e($id) ?>"><?= e($s['name']) ?></option><?php endforeach; ?></select></div>
      <div class="field" style="margin:0"><label>Dátum</label><input type="date" name="date" min="<?= $today ?>" required></div>
      <div class="field" style="margin:0"><label>Čas</label><input type="time" name="time" step="900" required></div>
      <div class="field" style="margin:0"><label>Meno</label><input name="name"></div>
      <div class="field" style="margin:0"><label>Telefón</label><input name="phone"></div>
      <button class="btn btn-sm">Pridať</button>
    </form>
  </div>

  <div class="panel" id="zatvorene">
    <h2>Zatvorené dni (dovolenka, sviatky)</h2>
    <form method="post" style="display:flex;gap:12px;flex-wrap:wrap;align-items:end;margin-bottom:16px">
      <?= csrf_field() ?><input type="hidden" name="action" value="close_day">
      <div class="field" style="margin:0"><label>Dátum</label><input type="date" name="date" min="<?= $today ?>" required></div>
      <button class="btn btn-sm">Uzavrieť deň</button>
    </form>
    <?php foreach ($closed as $d): if ($d < $today) continue; ?>
      <form method="post" style="display:inline-block;margin:0 8px 8px 0"><?= csrf_field() ?><input type="hidden" name="action" value="open_day"><input type="hidden" name="date" value="<?= e($d) ?>">
        <span class="pill cancelled"><?= e(sk_date($d)) ?> <button class="mini" style="padding:.1em .5em" title="Znova otvoriť">×</button></span></form>
    <?php endforeach; ?>
    <?php if (!array_filter($closed, fn($d) => $d >= $today)): ?><p style="color:var(--muted);margin:0">Žiadne uzavreté dni.</p><?php endif; ?>
  </div>
<?php admin_foot();
