<?php
/**
 * Spoločné funkcie: konfigurácia, ukladanie rezervácií (JSON so zamykaním),
 * výpočet voľných termínov.
 */

/** Konfigurácia: predvolené hodnoty z config.php + úpravy z administrácie (data/settings.json). */
function cfg(): array {
    static $c = null;
    if ($c === null) {
        $c = require __DIR__ . '/../config.php';
        $o = json_decode((string)@file_get_contents(__DIR__ . '/../data/settings.json'), true);
        if (is_array($o)) {
            if (!empty($o['salon']) && is_array($o['salon'])) $c['salon'] = array_merge($c['salon'], $o['salon']);
            if (!empty($o['booking']) && is_array($o['booking'])) $c['booking'] = array_merge($c['booking'], $o['booking']);
            if (!empty($o['hours']) && is_array($o['hours'])) {
                foreach ($o['hours'] as $d => $h) $c['hours'][(int)$d] = (is_array($h) && count($h) === 2) ? array_values($h) : null;
            }
            if (array_key_exists('hours_by_appointment', $o)) $c['hours_by_appointment'] = (bool)$o['hours_by_appointment'];
            if (!empty($o['services']) && is_array($o['services'])) $c['services'] = $o['services'];
            if (!empty($o['admin_password_hash'])) $c['admin_password_hash'] = $o['admin_password_hash'];
        }
    }
    return $c;
}

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

define('DATA_DIR', __DIR__ . '/../data');
define('BOOKINGS_FILE', DATA_DIR . '/bookings.json');
define('CLOSED_FILE', DATA_DIR . '/closed.json');
define('SETTINGS_FILE', DATA_DIR . '/settings.json');   // kontakty, hodiny, rezervácie, služby, heslo
define('TEXTS_FILE', DATA_DIR . '/texts.json');         // upravené texty (kľúč => text)
define('PAGES_FILE', DATA_DIR . '/pages.json');         // obsah podstránok služieb
define('GALLERY_FILE', DATA_DIR . '/gallery.json');     // galéria
define('UPLOAD_DIR', __DIR__ . '/../assets/img/uploads');

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
        // Najprv zakódujeme; ak by to zlyhalo, pôvodný súbor zostane nedotknutý (nikdy ho nevyprázdnime).
        $json = json_encode($result['data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE);
        if ($json === false) { flock($fh, LOCK_UN); fclose($fh); throw new RuntimeException('Dáta sa nepodarilo uložiť.'); }
        ftruncate($fh, 0); rewind($fh);
        fwrite($fh, $json);
        fflush($fh);
    }
    flock($fh, LOCK_UN); fclose($fh);
    return $result['return'] ?? null;
}

/* ---------- Ochrana osobných údajov: automatické mazanie starých údajov ---------- */

/** Maže rezervácie a správy z formulára staršie ako 12 mesiacov (podľa zásad ochrany údajov). Beží najviac raz za deň. */
function purge_old_data(): void {
    $mark = DATA_DIR . '/.purged';
    if (is_file($mark) && filemtime($mark) > time() - 86400) return;
    @touch($mark);
    $cut = date('Y-m-d', strtotime('-12 months'));
    foreach ([BOOKINGS_FILE => 'date', DATA_DIR . '/messages.json' => 'date'] as $file => $field) {
        if (!is_file($file)) continue;
        store_mutate($file, function (array $all) use ($cut, $field) {
            $keep = array_values(array_filter($all, fn($r) => substr((string)($r[$field] ?? '9999'), 0, 10) >= $cut));
            return count($keep) === count($all) ? [] : ['data' => $keep];
        });
    }
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
    if (!empty(cfg()['hours_by_appointment'])) return e(tx('hours.by_appointment'));
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


/* ---------- Obrázky ---------- */

/** Názov súboru obrázka; ak nemá príponu, pridá .jpg (spätná kompatibilita so starým zápisom). */
function img_file(string $f): string {
    $f = trim(str_replace(chr(92), '/', $f), '/');
    return pathinfo($f, PATHINFO_EXTENSION) === '' ? $f . '.jpg' : $f;
}
function img_url(string $f): string { return 'assets/img/' . img_file($f); }

/** Všetky dostupné fotky (okrem loga): relatívne cesty voči assets/img. */
function media_list(): array {
    $out = [];
    foreach (['' => __DIR__ . '/../assets/img', 'uploads/' => UPLOAD_DIR] as $prefix => $dir) {
        foreach (glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [] as $f) {
            $n = basename($f);
            if ($prefix === '' && in_array($n, ['logo.png', 'logo-square.png', 'logo.jpg'], true)) continue;
            $out[] = $prefix . $n;
        }
    }
    natsort($out);
    return array_values($out);
}

/* ---------- Texty ---------- */

function texts_registry(): array {
    static $flat = null;
    if ($flat === null) {
        $flat = [];
        foreach (require __DIR__ . '/texts.php' as $page => $sections)
            foreach ($sections as $section => $items)
                foreach ($items as $k => $def) $flat[$k] = $def + ['group' => $page, 'section' => $section];
    }
    return $flat;
}

/** Surový text (s doplnenými {name}, {phone}, {email}, {address}, {ico}). */
function tx(string $key): string {
    static $ov = null;
    if ($ov === null) $ov = store_read(TEXTS_FILE);
    $reg = texts_registry();
    $v = (isset($ov[$key]) && trim((string)$ov[$key]) !== '') ? (string)$ov[$key] : (string)($reg[$key][1] ?? '');
    $S = cfg()['salon'];
    return strtr($v, ['{name}' => $S['name'], '{phone}' => $S['phone'], '{email}' => $S['email'], '{address}' => $S['address'], '{ico}' => $S['ico'] ?? '']);
}
/** **tučné** → <b>, *kurzíva* → <em>, všetko ostatné je escapované. */
function fmt(string $s): string {
    $h = preg_replace('/\*\*(.+?)\*\*/su', '<b>$1</b>', e($s));
    return preg_replace('/(?<![*\w])\*([^*\n]+?)\*(?![*\w])/u', '<em>$1</em>', $h);
}
function t(string $key): string { return fmt(tx($key)); }
/** Riadky textu ako pole (prázdne vynechá). */
function lines(string $s): array { return array_values(array_filter(array_map('trim', preg_split('/\R/u', $s)), fn($x) => $x !== '')); }
function tl(string $key): array { return lines(tx($key)); }
/** Riadky v tvare "Nadpis | text" ako pole dvojíc. */
function pairs(string $s): array {
    return array_map(function ($l) { $p = explode('|', $l, 2); return [trim($p[0]), trim($p[1] ?? '')]; }, lines($s));
}
function tp(string $key): array { return pairs(tx($key)); }
function timg(string $key): string { return img_url(tx($key)); }
/** Odseky oddelené prázdnym riadkom; "## Nadpis" = nadpis. Vráti HTML. */
function rich(string $s): string {
    $out = '';
    foreach (preg_split('/\R\s*\R/u', trim($s)) as $block) {
        $block = trim($block);
        if ($block === '') continue;
        $out .= str_starts_with($block, '## ') ? '<h2>' . fmt(substr($block, 3)) . '</h2>' : '<p>' . nl2br(fmt($block)) . '</p>';
    }
    return $out;
}
function trich(string $key): string { return rich(tx($key)); }

/* ---------- Služby a ich podstránky ---------- */

/** Kľúč podstránky pre službu: pôvodné súbory podľa názvu, nové služby cez sluzba.php?id=... */
function page_key(string $id, array $s): string {
    return str_starts_with($s['page'] ?? '', 'sluzba.php?id=') ? 'svc:' . $id : (string)$s['page'];
}

/** Menu služieb (unikátne podstránky v poradí služieb): [[href, popis], ...]. */
function service_menu(): array {
    $out = [];
    foreach (cfg()['services'] as $s) if (!isset($out[$s['page']])) $out[$s['page']] = [$s['page'], $s['menu'] ?? $s['name']];
    return array_values($out);
}

/** Krátky identifikátor kategórie pre filter (bez diakritiky). */
function cat_slug(string $c): string {
    $c = strtr(mb_strtolower($c), ['á'=>'a','ä'=>'a','č'=>'c','ď'=>'d','é'=>'e','í'=>'i','ĺ'=>'l','ľ'=>'l','ň'=>'n','ó'=>'o','ô'=>'o','ŕ'=>'r','š'=>'s','ť'=>'t','ú'=>'u','ý'=>'y','ž'=>'z']);
    return trim(preg_replace('~[^a-z0-9]+~', '-', $c), '-');
}

/** Služby podľa kategórií (v poradí prvého výskytu): [názov kategórie => [id => služba, ...]]. Služby bez kategórie idú do „Ostatné“. */
function service_categories(): array {
    $out = [];
    foreach (cfg()['services'] as $id => $s) {
        $c = trim((string)($s['category'] ?? '')) ?: 'Ostatné';
        $out[$c][(string)$id] = $s;
    }
    return $out;
}

/**
 * Služby zoskupené podľa podstránky (karty na úvode a v prehľade služieb):
 * page => ['page','name','short','img','icon','min','count','ids'].
 */
function service_groups(): array {
    $out = [];
    foreach (cfg()['services'] as $id => $s) {
        $p = (string)$s['page'];
        if (!isset($out[$p])) $out[$p] = ['page' => $p, 'first' => (string)$id, 'name' => $s['name'], 'short' => $s['short'], 'img' => $s['img'], 'icon' => $s['icon'], 'min' => (float)$s['price'], 'count' => 0, 'ids' => []];
        $out[$p]['min'] = min($out[$p]['min'], (float)$s['price']);
        $out[$p]['count']++;
        $out[$p]['ids'][] = (string)$id;
    }
    foreach ($out as $p => $g) {
        if ($g['count'] > 1) {   // podstránka s viac službami: názov a popis berieme z podstránky
            $P = page_content(page_key($g['first'], cfg()['services'][$g['first']]));
            $out[$p]['name'] = $P['title'];
            $out[$p]['short'] = $P['lead'];
        }
    }
    return $out;
}

/** Podstránky služieb: kľúč => ['label', 'services' => [id, ...]]. */
function service_pages(): array {
    $out = [];
    foreach (cfg()['services'] as $id => $s) {
        $k = page_key((string)$id, $s);
        $out[$k]['label'] = $out[$k]['label'] ?? ($s['menu'] ?? $s['name']);
        $out[$k]['services'][] = (string)$id;
    }
    return $out;
}

/** Obsah podstránky: predvolený (service-content.php) + úpravy z administrácie. */
function page_content(string $key): array {
    $all = require __DIR__ . '/service-content.php';
    $C = cfg();
    if (isset($all[$key])) $base = $all[$key];
    else {
        $id = str_starts_with($key, 'svc:') ? substr($key, 4) : '';
        $s = $C['services'][$id] ?? ['name' => 'Služba', 'short' => ''];
        $base = ['active' => 'sluzby', 'title' => $s['name'], 'desc' => $s['short'], 'h1' => $s['name'], 'lead' => $s['short'], 'services' => [$id],
                 'intro' => [], 'note' => '', 'includes' => [], 'steps' => [], 'tips' => [], 'photos' => [], 'faq' => []];
    }
    $ov = store_read(PAGES_FILE)[$key] ?? [];
    foreach ($ov as $f => $v) if ($v !== null) $base[$f] = $v;
    return $base;
}

/* ---------- Galéria ---------- */

/** Galéria: ['cats' => [slug => názov], 'items' => [['file','cat','label','home'], ...]]. */
function gallery_data(): array {
    $g = store_read(GALLERY_FILE);
    if (isset($g['items'], $g['cats'])) return $g;
    $cats = ['pedikura' => ['Pedikúra', 10], 'brusenie' => ['Pedikúra – päty', 4], 'mykoza' => ['Ošetrenie nechtov', 2], 'bradavice' => ['Kurie oká a otlaky', 4], 'spona' => ['Špona', 6]];
    $home = ['pedikura-2', 'pedikura-5', 'brusenie-2', 'pedikura-8', 'spona-4', 'pedikura-10', 'bradavice-2', 'pedikura-3', 'mykoza-1', 'pedikura-7', 'spona-1', 'pedikura-9'];
    $items = [];
    for ($i = 1, $max = max(array_column($cats, 1)); $i <= $max; $i++)
        foreach ($cats as $k => [$label, $n]) if ($i <= $n) $items[] = ['file' => "$k-$i.jpg", 'cat' => $k, 'label' => $label, 'home' => in_array("$k-$i", $home, true)];
    return ['cats' => array_map(fn($c) => $c[0], $cats), 'items' => $items];
}

/** Zmena súboru nastavení: callback dostane aktuálne pole a vráti nové. */
function settings_update(callable $fn): void {
    store_mutate(SETTINGS_FILE, fn(array $s) => ['data' => $fn($s)]);
}

/** Text e-mailu z registra s doplnenými značkami, napr. mail_text('mail.confirmed', ['{client}' => 'Anna']). */
function mail_text(string $key, array $vars): string { return strtr(tx($key), $vars); }

/* ---------- Recenzie z Google ---------- */

define('REVIEWS_FILE', DATA_DIR . '/reviews.json');

/**
 * Recenzie: ['enabled', 'url', 'rating', 'count', 'items' => [['name','text','stars','when'], ...]].
 * Kým ich majiteľ neuloží v administrácii, sú to len UKÁŽKOVÉ texty a sekcia je vypnutá.
 */
function reviews_data(): array {
    $d = store_read(REVIEWS_FILE);
    if (isset($d['items'])) return $d + ['enabled' => false, 'url' => '', 'rating' => '', 'count' => ''];
    return ['enabled' => false, 'demo' => true, 'url' => '', 'rating' => '', 'count' => '', 'items' => [
        ['name' => 'Testovacia recenzia 1', 'stars' => 5, 'when' => 'pred mesiacom', 'text' => 'TESTOVACÍ TEXT – nahraďte skutočnou recenziou z Google. Takto bude vyzerať karta s recenziou klienta, vrátane hviezdičiek a loga Google.'],
        ['name' => 'Testovacia recenzia 2', 'stars' => 5, 'when' => 'pred 2 mesiacmi', 'text' => 'TESTOVACÍ TEXT – sem patrí skutočné hodnotenie z vášho Google profilu, skopírované tak, ako ho klient napísal.'],
        ['name' => 'Testovacia recenzia 3', 'stars' => 4, 'when' => 'pred 3 mesiacmi', 'text' => 'TESTOVACÍ TEXT – recenzie upravíte alebo zmažete v administrácii v sekcii Recenzie.'],
        ['name' => 'Testovacia recenzia 4', 'stars' => 5, 'when' => 'pred 4 mesiacmi', 'text' => 'TESTOVACÍ TEXT – slider sa posúva šípkami alebo ťahaním myšou, na mobile prstom.'],
    ]];
}

/* ---------- Náhľady fotiek (rýchlejšie načítanie kariet a galérie) ---------- */

/** Názov súboru náhľadu pre fotku (relatívne voči assets/img): t/<názov>.jpg */
function thumb_name(string $file): string { return 't/' . str_replace('/', '-', img_file($file)); }

/** Vytvorí menší náhľad (max. $w px na šírku). Vráti true, ak náhľad existuje. */
function make_thumb(string $file, int $w = 640): bool {
    $src = __DIR__ . '/../assets/img/' . img_file($file);
    $dst = __DIR__ . '/../assets/img/' . thumb_name($file);
    if (!is_file($src)) return false;
    if (is_file($dst) && filemtime($dst) >= filemtime($src)) return true;
    $im = @imagecreatefromstring((string)file_get_contents($src));
    if (!$im) return false;
    $sw = imagesx($im); $sh = imagesy($im);
    if ($sw <= $w) { $nw = $sw; $nh = $sh; } else { $nw = $w; $nh = (int)round($sh * $w / $sw); }
    $out = imagecreatetruecolor($nw, $nh);
    imagefill($out, 0, 0, imagecolorallocate($out, 255, 255, 255));
    imagecopyresampled($out, $im, 0, 0, 0, 0, $nw, $nh, $sw, $sh);
    if (!is_dir(dirname($dst))) @mkdir(dirname($dst), 0775, true);
    $ok = imagejpeg($out, $dst, 78);
    return $ok;
}

/** Adresa náhľadu fotky (vytvorí ho pri prvom použití); ak sa nedá, vráti originál. */
function img_thumb(string $file): string {
    return make_thumb($file) ? 'assets/img/' . thumb_name($file) : img_url($file);
}

/** Zoznam fotiek z textového poľa typu "photos" (jeden súbor na riadok); neexistujúce súbory vynechá. */
function tphotos(string $key): array {
    return array_values(array_filter(array_map('img_file', lines(tx($key))), fn($f) => is_file(__DIR__ . '/../assets/img/' . $f)));
}

require_once __DIR__ . '/mail.php';
require_once __DIR__ . '/content.php';
