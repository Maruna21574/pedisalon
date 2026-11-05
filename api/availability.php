<?php
require __DIR__ . '/../inc/lib.php';

$c = cfg();
$sid = $_GET['service'] ?? '';
if (!isset($c['services'][$sid])) json_out(['error' => 'Neplatná služba'], 400);
$dur = (int)$c['services'][$sid]['duration'];

$bookings = store_read(BOOKINGS_FILE);
$closed = store_read(CLOSED_FILE);

// ?date=YYYY-MM-DD  → voľné časy
if (!empty($_GET['date'])) {
    $d = $_GET['date'];
    if (!is_valid_date($d)) json_out(['error' => 'Neplatný dátum'], 400);
    json_out(['date' => $d, 'slots' => free_slots($d, $dur, $bookings, $closed)]);
}

// ?month=YYYY-MM → dni s voľným termínom
$m = $_GET['month'] ?? date('Y-m');
if (!preg_match('/^\d{4}-\d{2}$/', $m) || !is_valid_date($m . '-01')) json_out(['error' => 'Neplatný mesiac'], 400);
$days = [];
$n = (int)date('t', strtotime($m . '-01'));
for ($i = 1; $i <= $n; $i++) {
    $d = sprintf('%s-%02d', $m, $i);
    $days[$d] = count(free_slots($d, $dur, $bookings, $closed));
}
json_out(['month' => $m, 'days' => $days]);
