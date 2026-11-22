<?php
require __DIR__ . '/../inc/lib.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_out(['error' => 'Metóda nie je povolená'], 405);

$c = cfg();
purge_old_data();
$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) json_out(['error' => 'Neplatná požiadavka'], 400);

// Anti-spam: honeypot + minimálny čas vyplnenia
if (!empty($in['website'])) json_out(['ok' => true]); // tvárime sa že OK
if ((int)($in['elapsed'] ?? 0) < 4) json_out(['error' => 'Formulár bol odoslaný príliš rýchlo, skúste to znova.'], 429);

$sid  = (string)($in['service'] ?? '');
$date = (string)($in['date'] ?? '');
$time = (string)($in['time'] ?? '');
$name = trim((string)($in['name'] ?? ''));
$phone = trim((string)($in['phone'] ?? ''));
$email = trim((string)($in['email'] ?? ''));
$note = trim((string)($in['note'] ?? ''));
$consent = !empty($in['consent']);

$errors = [];
if (!isset($c['services'][$sid])) $errors['service'] = 'Vyberte službu.';
if (!is_valid_date($date)) $errors['date'] = 'Vyberte dátum.';
if (!preg_match('/^\d{2}:\d{2}$/', $time)) $errors['time'] = 'Vyberte čas.';
if (mb_strlen($name) < 3 || mb_strlen($name) > 80) $errors['name'] = 'Zadajte meno a priezvisko.';
if (!preg_match('/^\+?[0-9 ()\-]{9,18}$/', $phone)) $errors['phone'] = 'Zadajte platné telefónne číslo.';
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'E-mail nie je platný.';
if (mb_strlen($note) > 500) $errors['note'] = 'Poznámka je príliš dlhá.';
if (!$consent) $errors['consent'] = 'Je potrebný súhlas so spracovaním údajov.';
if ($errors) json_out(['error' => 'Skontrolujte vyplnené údaje.', 'fields' => $errors], 422);

$svc = $c['services'][$sid];
$status = $c['booking']['auto_confirm'] ? 'confirmed' : 'pending';
$id = bin2hex(random_bytes(6));

$result = store_mutate(BOOKINGS_FILE, function (array $all) use ($date, $time, $svc, $sid, $name, $phone, $email, $note, $status, $id) {
    $closed = store_read(CLOSED_FILE);
    // Kontrola dostupnosti priamo pod zámkom (ochrana pred dvojitou rezerváciou)
    if (!in_array($time, free_slots($date, (int)$svc['duration'], $all, $closed), true)) {
        return ['return' => false];
    }
    // Limit: max. 3 aktívne budúce rezervácie na jedno číslo
    $digits = preg_replace('/\D/', '', $phone);
    $active = 0;
    foreach ($all as $b) {
        if (($b['status'] ?? '') !== 'cancelled' && preg_replace('/\D/', '', $b['phone']) === $digits && $b['date'] >= date('Y-m-d')) $active++;
    }
    if ($active >= 3) return ['return' => 'limit'];

    $all[] = [
        'id' => $id, 'service' => $sid, 'service_name' => $svc['name'], 'duration' => (int)$svc['duration'],
        'price' => $svc['price'], 'date' => $date, 'start' => $time,
        'name' => $name, 'phone' => $phone, 'email' => $email, 'note' => $note,
        'status' => $status, 'created' => date('c'),
    ];
    return ['data' => $all, 'return' => true];
});

if ($result === 'limit') json_out(['error' => 'Na toto telefónne číslo už existuje viac aktívnych rezervácií. Zavolajte nám prosím.'], 429);
if ($result !== true) json_out(['error' => 'Tento termín už bol medzitým obsadený. Vyberte prosím iný čas.', 'conflict' => true], 409);

// Notifikácie (HTML e-maily: majiteľke aj klientovi)
$when = sk_date($date) . ' o ' . $time;
$endTime = min_to_hm(hm_to_min($time) + (int)$svc['duration']);
$bk = ['id' => $id, 'service_name' => $svc['name'], 'duration' => (int)$svc['duration'], 'price' => $svc['price'], 'date' => $date, 'start' => $time,
       'name' => $name, 'phone' => $phone, 'email' => $email, 'note' => $note, 'status' => $status];
send_admin_booking_mail($bk);
if ($email) send_client_mail('received', $bk);

json_out(['ok' => true, 'status' => $status, 'when' => $when, 'service' => $svc['name'], 'end' => $endTime]);
