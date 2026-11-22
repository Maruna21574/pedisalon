<?php
require __DIR__ . '/../inc/lib.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_out(['error' => 'Metóda nie je povolená'], 405);
purge_old_data();
$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) json_out(['error' => 'Neplatná požiadavka'], 400);
if (!empty($in['website'])) json_out(['ok' => true]);
if ((int)($in['elapsed'] ?? 0) < 3) json_out(['error' => 'Odoslané príliš rýchlo, skúste to znova.'], 429);

$name = trim((string)($in['name'] ?? ''));
$email = trim((string)($in['email'] ?? ''));
$phone = trim((string)($in['phone'] ?? ''));
$msg = trim((string)($in['message'] ?? ''));
if (mb_strlen($name) < 2) json_out(['error' => 'Zadajte meno.'], 422);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_out(['error' => 'Zadajte platný e-mail.'], 422);
if (mb_strlen($msg) < 5 || mb_strlen($msg) > 3000) json_out(['error' => 'Napíšte správu.'], 422);
if (empty($in['consent'])) json_out(['error' => 'Potvrďte súhlas so spracovaním údajov.'], 422);

$ok = send_admin_contact_mail($name, $email, $phone, $msg);
if (!$ok) {
    // Záloha: uložiť, ak mail() na hostingu zlyhá
    store_mutate(DATA_DIR . '/messages.json', function (array $all) use ($name, $email, $phone, $msg) {
        $all[] = ['date' => date('c'), 'name' => $name, 'email' => $email, 'phone' => $phone, 'message' => $msg];
        return ['data' => $all];
    });
}
json_out(['ok' => true]);
