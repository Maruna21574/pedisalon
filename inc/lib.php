<?php
/**
 * Spoločné funkcie: konfigurácia, ukladanie rezervácií (JSON so zamykaním),
 * výpočet voľných termínov.
 */

function cfg(): array {
    static $c = null;
    if ($c === null) { $c = require __DIR__ . '/../config.php'; }
    return $c;
}

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

define('DATA_DIR', __DIR__ . '/../data');
define('BOOKINGS_FILE', DATA_DIR . '/bookings.json');
define('CLOSED_FILE', DATA_DIR . '/closed.json');

/* ---------- Úložisko ---------- */

function store_read(string $file): array {
    if (!is_file($file)) return [];
    $raw = @file_get_contents($file);
    $d = json_decode((string)$raw, true);
    return is_array($d) ? $d : [];
}

/** Atómová zmena súboru: callback dostane pole a vráti nové pole (alebo null = nezapisovať). */
function store_mutate(string $file, callable $fn) {
    if (!is_dir(DATA_DIR)) @mkdir(DATA_DIR, 0775, true);
    $fh = fopen($file, 'c+');
    if (!$fh) throw new RuntimeException('Nedá sa otvoriť úložisko.');
    flock($fh, LOCK_EX);
    $raw = stream_get_contents($fh);
    $data = json_decode((string)$raw, true);
    if (!is_array($data)) $data = [];
    $result = $fn($data);
    if (is_array($result) && array_key_exists('data', $result) && $result['data'] !== null) {
        ftruncate($fh, 0); rewind($fh);
        fwrite($fh, json_encode($result['data'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        fflush($fh);
    }
    flock($fh, LOCK_UN); fclose($fh);
    return $result['return'] ?? null;
}

/* ---------- Čas ---------- */

function hm_to_min(string $hm): int { [$h, $m] = array_map('intval', explode(':', $hm)); return $h * 60 + $m; }
function min_to_hm(int $m): string { return sprintf('%02d:%02d', intdiv($m, 60), $m % 60); }

function is_valid_date(string $d): bool {
    $dt = DateTime::createFromFormat('Y-m-d', $d);
    return $dt && $dt->format('Y-m-d') === $d;
}

function day_hours(string $date): ?array {
    $c = cfg();
    $dow = (int)(new DateTime($date))->format('N');
    return $c['hours'][$dow] ?? null;
}

/* ---------- Dostupnosť ---------- */

/** Aktívne (nezrušené) rezervácie daného dňa ako intervaly [od, do) v minútach vrátane prestávky. */
function busy_intervals(array $bookings, string $date): array {
    $buf = (int)cfg()['booking']['buffer_min'];
    $out = [];
    foreach ($bookings as $b) {
        if ($b['date'] !== $date || ($b['status'] ?? '') === 'cancelled') continue;
        $out[] = [hm_to_min($b['start']), hm_to_min($b['start']) + (int)$b['duration'] + $buf];
    }
    return $out;
}

function is_closed_day(string $date, ?array $closed = null): bool {
    $closed = $closed ?? store_read(CLOSED_FILE);
    return in_array($date, $closed, true);
}

/** Zoznam voľných začiatkov (HH:MM) pre danú službu a deň. */
function free_slots(string $date, int $duration, ?array $bookings = null, ?array $closed = null): array {
    $c = cfg();
    $hours = day_hours($date);
    if (!$hours || is_closed_day($date, $closed)) return [];
    $bookings = $bookings ?? store_read(BOOKINGS_FILE);
    $busy = busy_intervals($bookings, $date);
    $step = (int)$c['booking']['slot_step'];
    $open = hm_to_min($hours[0]); $close = hm_to_min($hours[1]);
    $earliest = time() + $c['booking']['min_lead_hours'] * 3600;
    $latestTs = strtotime('+' . (int)$c['booking']['max_days_ahead'] . ' days 23:59:59');
    $slots = [];
    for ($t = $open; $t + $duration <= $close; $t += $step) {
        $ts = strtotime($date . ' ' . min_to_hm($t));
        if ($ts < $earliest || $ts > $latestTs) continue;
        $end = $t + $duration;
        $ok = true;
        foreach ($busy as [$bs, $be]) {
            if ($t < $be && $end > $bs) { $ok = false; break; }
        }
        if ($ok) $slots[] = min_to_hm($t);
    }
    return $slots;
}

/* ---------- Pomocné pre šablóny ---------- */

const DAY_NAMES = [1 => 'Pondelok', 2 => 'Utorok', 3 => 'Streda', 4 => 'Štvrtok', 5 => 'Piatok', 6 => 'Sobota', 7 => 'Nedeľa'];

/** Stručné otváracie hodiny pre pätičku (zoskupí rovnaké po sebe idúce dni). */
function hours_summary(): string {
    $short = [1 => 'Po', 2 => 'Ut', 3 => 'St', 4 => 'Št', 5 => 'Pi', 6 => 'So', 7 => 'Ne'];
    $groups = [];
    foreach (cfg()['hours'] as $d => $h) {
        if (!$h) continue;
        $label = str_replace(':00', ':00', ltrim($h[0], '0')) . '–' . ltrim($h[1], '0');
        $last = count($groups) - 1;
        if ($last >= 0 && $groups[$last]['label'] === $label && $groups[$last]['to'] === $d - 1) { $groups[$last]['to'] = $d; }
        else $groups[] = ['from' => $d, 'to' => $d, 'label' => $label];
    }
    $out = [];
    foreach ($groups as $g) {
        $days = $g['from'] === $g['to'] ? $short[$g['from']] : $short[$g['from']] . '–' . $short[$g['to']];
        $out[] = $days . ' ' . $g['label'];
    }
    return implode('<br>', $out);
}

function money($n): string { return rtrim(rtrim(number_format((float)$n, 2, ',', ' '), '0'), ',') . ' €'; }

function sk_date(string $date): string {
    $days = ['pondelok','utorok','streda','štvrtok','piatok','sobota','nedeľa'];
    $months = ['januára','februára','marca','apríla','mája','júna','júla','augusta','septembra','októbra','novembra','decembra'];
    $d = new DateTime($date);
    return $days[(int)$d->format('N') - 1] . ' ' . (int)$d->format('j') . '. ' . $months[(int)$d->format('n') - 1] . ' ' . $d->format('Y');
}

function json_out(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function send_mail(string $to, string $subject, string $body, string $replyTo = ''): bool {
    $c = cfg()['salon'];
    $host = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $host = preg_replace('/:\d+$/', '', $host);
    $from = 'rezervacie@' . $host;
    $headers = [
        'From: ' . '=?UTF-8?B?' . base64_encode($c['name']) . '?=' . " <$from>",
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
    ];
    if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) $headers[] = 'Reply-To: ' . $replyTo;
    return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
}
