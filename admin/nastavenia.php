<?php
require __DIR__ . '/_boot.php';

$valid_time = fn($t) => is_string($t) && preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $t);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $a = $_POST['action'] ?? '';

    if ($a === 'salon') {
        $in = $_POST['salon'] ?? [];
        $f = fn($k) => trim((string)($in[$k] ?? ''));
        $errs = [];
        if ($f('name') === '') $errs[] = 'Názov salónu je povinný.';
        if ($f('email') !== '' && !filter_var($f('email'), FILTER_VALIDATE_EMAIL)) $errs[] = 'E-mail nie je platný.';
        foreach (['facebook', 'instagram'] as $k) if ($f($k) !== '' && !preg_match('#^https?://#i', $f($k))) $errs[] = ucfirst($k) . ' – odkaz musí začínať http:// alebo https://.';
        // Mapa: stačí vložiť celý <iframe> z Google Máp, adresa src sa vyberie sama
        $maps = $f('maps_embed');
        if (preg_match('/src=["\']([^"\']+)["\']/i', $maps, $m)) $maps = html_entity_decode($m[1]);
        if ($maps !== '' && !preg_match('#^https://#i', $maps)) $errs[] = 'Odkaz na mapu musí začínať https://.';
        if ($errs) { flash(implode(' ', $errs), 'bad'); redirect('nastavenia.php'); }
        $salon = ['name' => $f('name'), 'tagline' => $f('tagline'), 'phone' => $f('phone'), 'phone_raw' => preg_replace('/[^\d+]/', '', $f('phone')),
                  'email' => $f('email'), 'address' => $f('address'), 'city' => $f('city'), 'facebook' => $f('facebook'), 'instagram' => $f('instagram'),
                  'maps_embed' => $maps, 'ico' => $f('ico')];
        settings_update(function (array $s) use ($salon) { $s['salon'] = $salon; return $s; });
        flash('Údaje salónu uložené.');
    } elseif ($a === 'hours') {
        $hours = []; $errs = [];
        foreach (DAY_NAMES as $d => $n) {
            if (!empty($_POST['closed'][$d])) { $hours[$d] = null; continue; }
            $from = $_POST['from'][$d] ?? ''; $to = $_POST['to'][$d] ?? '';
            if (!$valid_time($from) || !$valid_time($to) || hm_to_min($to) <= hm_to_min($from)) { $errs[] = "$n: zadajte platný čas (koniec musí byť po začiatku)."; continue; }
            $hours[$d] = [$from, $to];
        }
        if ($errs) { flash(implode(' ', $errs), 'bad'); redirect('nastavenia.php'); }
        settings_update(function (array $s) use ($hours) { $s['hours'] = $hours; return $s; });
        flash('Otváracie hodiny uložené.');
    } elseif ($a === 'booking') {
        $b = $_POST['booking'] ?? [];
        $booking = [
            'slot_step'      => max(5, min(240, (int)($b['slot_step'] ?? 30))),
            'min_lead_hours' => max(0, min(720, (int)($b['min_lead_hours'] ?? 3))),
            'max_days_ahead' => max(1, min(365, (int)($b['max_days_ahead'] ?? 60))),
            'auto_confirm'   => !empty($b['auto_confirm']),
            'buffer_min'     => max(0, min(120, (int)($b['buffer_min'] ?? 10))),
        ];
        settings_update(function (array $s) use ($booking) { $s['booking'] = $booking; return $s; });
        flash('Nastavenia rezervácií uložené.');
    } elseif ($a === 'password') {
        $cur = (string)($_POST['current'] ?? ''); $new = (string)($_POST['new'] ?? ''); $rep = (string)($_POST['repeat'] ?? '');
        if (!password_verify($cur, $C['admin_password_hash'])) flash('Súčasné heslo nie je správne.', 'bad');
        elseif (mb_strlen($new) < 8) flash('Nové heslo musí mať aspoň 8 znakov.', 'bad');
        elseif ($new !== $rep) flash('Nové heslá sa nezhodujú.', 'bad');
        else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            settings_update(function (array $s) use ($hash) { $s['admin_password_hash'] = $hash; return $s; });
            session_regenerate_id(true);
            flash('Heslo bolo zmenené.');
        }
    }
    redirect('nastavenia.php');
}

$S = $C['salon']; $B = $C['booking'];
admin_head('Nastavenia', 'nastavenia');
?>
<div class="panel" id="salon">
  <h2>Údaje salónu</h2>
  <form method="post">
    <?= csrf_field() ?><input type="hidden" name="action" value="salon">
    <div class="grid-form">
      <div class="field"><label>Názov salónu</label><input name="salon[name]" value="<?= e($S['name']) ?>" required></div>
      <div class="field"><label>Slogan</label><input name="salon[tagline]" value="<?= e($S['tagline']) ?>"></div>
      <div class="field"><label>Telefón</label><input name="salon[phone]" value="<?= e($S['phone']) ?>"></div>
      <div class="field"><label>E-mail (sem chodia rezervácie a správy)</label><input type="email" name="salon[email]" value="<?= e($S['email']) ?>"></div>
      <div class="field"><label>Adresa</label><input name="salon[address]" value="<?= e($S['address']) ?>"></div>
      <div class="field"><label>Mesto</label><input name="salon[city]" value="<?= e($S['city']) ?>"></div>
      <div class="field"><label>Facebook (odkaz)</label><input name="salon[facebook]" value="<?= e($S['facebook']) ?>"></div>
      <div class="field"><label>Instagram (odkaz)</label><input name="salon[instagram]" value="<?= e($S['instagram']) ?>"></div>
      <div class="field"><label>IČO / fakturačné údaje (voliteľné)</label><input name="salon[ico]" value="<?= e($S['ico']) ?>"></div>
    </div>
    <div class="field"><label>Mapa (Google Mapy → Zdieľať → Vložiť mapu → skopírujte celý kód)</label><textarea name="salon[maps_embed]" rows="2"><?= e($S['maps_embed']) ?></textarea></div>
    <button class="btn btn-sm">Uložiť údaje salónu</button>
  </form>
</div>

<div class="panel" id="hodiny">
  <h2>Otváracie hodiny</h2>
  <form method="post">
    <?= csrf_field() ?><input type="hidden" name="action" value="hours">
    <table class="tbl hours-edit">
      <?php foreach (DAY_NAMES as $d => $n): $h = $C['hours'][$d] ?? null; ?>
      <tr>
        <td style="width:9em"><b><?= $n ?></b></td>
        <td><label class="check" style="margin:0"><input type="checkbox" name="closed[<?= $d ?>]" value="1" <?= $h ? '' : 'checked' ?>> <span>Zatvorené</span></label></td>
        <td><input type="time" name="from[<?= $d ?>]" value="<?= e($h[0] ?? '09:00') ?>"> – <input type="time" name="to[<?= $d ?>]" value="<?= e($h[1] ?? '17:00') ?>"></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <p style="color:var(--muted);font-size:.9rem">Podľa týchto hodín sa ponúkajú voľné termíny v online rezervácii. Jednotlivé dni (dovolenka, sviatky) zatvoríte v sekcii Rezervácie.</p>
    <button class="btn btn-sm">Uložiť hodiny</button>
  </form>
</div>

<div class="panel">
  <h2>Online rezervácie</h2>
  <form method="post">
    <?= csrf_field() ?><input type="hidden" name="action" value="booking">
    <div class="grid-form">
      <div class="field"><label>Krok začiatkov termínov (min)</label><input type="number" name="booking[slot_step]" value="<?= (int)$B['slot_step'] ?>" min="5" max="240" step="5"></div>
      <div class="field"><label>Prestávka medzi klientmi (min)</label><input type="number" name="booking[buffer_min]" value="<?= (int)$B['buffer_min'] ?>" min="0" max="120"></div>
      <div class="field"><label>Najskôr o koľko hodín od teraz</label><input type="number" name="booking[min_lead_hours]" value="<?= (int)$B['min_lead_hours'] ?>" min="0" max="720"></div>
      <div class="field"><label>Na koľko dní dopredu sa dá rezervovať</label><input type="number" name="booking[max_days_ahead]" value="<?= (int)$B['max_days_ahead'] ?>" min="1" max="365"></div>
    </div>
    <label class="check"><input type="checkbox" name="booking[auto_confirm]" value="1" <?= $B['auto_confirm'] ? 'checked' : '' ?>> <span>Rezervácie potvrdzovať automaticky (inak čakajú na vaše potvrdenie)</span></label>
    <button class="btn btn-sm">Uložiť nastavenia rezervácií</button>
  </form>
</div>

<div class="panel" id="heslo">
  <h2>Zmena hesla do administrácie</h2>
  <form method="post" class="grid-form" style="align-items:end">
    <?= csrf_field() ?><input type="hidden" name="action" value="password">
    <div class="field"><label>Súčasné heslo</label><input type="password" name="current" autocomplete="current-password" required></div>
    <div class="field"><label>Nové heslo (min. 8 znakov)</label><input type="password" name="new" autocomplete="new-password" minlength="8" required></div>
    <div class="field"><label>Nové heslo znova</label><input type="password" name="repeat" autocomplete="new-password" minlength="8" required></div>
    <div class="field"><button class="btn btn-sm">Zmeniť heslo</button></div>
  </form>
</div>
<?php admin_foot();
