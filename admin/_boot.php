<?php
/**
 * Spoločný základ administrácie: relácia, prihlásenie, CSRF, rozloženie stránky a pomocné funkcie.
 * Každá stránka administrácie ho načíta ako prvé; neprihlásený návštevník uvidí len prihlasovací formulár.
 */
require __DIR__ . '/../inc/lib.php';
session_set_cookie_params(['httponly' => true, 'samesite' => 'Strict', 'secure' => !empty($_SERVER['HTTPS'])]);
session_name('pedi_admin');
session_start();
require_once __DIR__ . '/../inc/lib.php';
purge_old_data();
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');

$C = cfg();

if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

function check_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(400); exit('Neplatný token.'); }
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">'; }

/** Správa zobrazená po presmerovaní. */
function flash(string $msg, string $type = 'ok'): void { $_SESSION['flash'] = [$msg, $type]; }
function redirect(string $to): void { header('Location: ' . $to); exit; }

/* ---- Prihlásenie / odhlásenie ---- */
if (isset($_GET['logout'])) { session_destroy(); redirect('index.php'); }

if (empty($_SESSION['ok'])) {
    $err = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        // Jednoduché spomalenie hádania hesla
        $_SESSION['fails'] = $_SESSION['fails'] ?? 0;
        if ($_SESSION['fails'] >= 5) { sleep(3); }
        if (password_verify($_POST['password'], $C['admin_password_hash'])) {
            session_regenerate_id(true); $_SESSION['ok'] = true; $_SESSION['fails'] = 0; $_SESSION['csrf'] = bin2hex(random_bytes(16));
            redirect(basename($_SERVER['SCRIPT_NAME']));
        }
        $_SESSION['fails']++; $err = 'Nesprávne heslo.';
    }
    ?><!DOCTYPE html><html lang="sk"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title>Administrácia | <?= e($C['salon']['name']) ?></title>
<link rel="stylesheet" href="../assets/css/style.css?v=14"><link rel="stylesheet" href="../assets/css/fonts.css?v=1"></head>
<body class="admin-body"><form method="post" class="login-box">
<img src="../assets/img/logo.png" alt=""><h1 style="font-size:2rem">Administrácia</h1>
<?php if ($err): ?><div class="alert bad"><?= e($err) ?></div><?php endif; ?>
<div class="field" style="text-align:left"><label for="pw">Heslo</label><input id="pw" type="password" name="password" autofocus required></div>
<button class="btn" style="width:100%">Prihlásiť sa</button></form></body></html><?php
    exit;
}

/* ---------- Rozloženie ---------- */

function admin_head(string $title, string $active, string $badge = '', string $intro = ''): void {
    global $C;
    $tabs = ['index' => ['index.php', 'Prehľad'], 'rezervacie' => ['rezervacie.php', 'Rezervácie'], 'sluzby' => ['sluzby.php', 'Služby a ceny'],
             'texty' => ['texty.php', 'Texty webu'], 'poradna' => ['poradna.php', 'Poradňa'], 'certifikaty' => ['certifikaty.php', 'Certifikáty'],
             'recenzie' => ['recenzie.php', 'Recenzie'], 'galeria' => ['galeria.php', 'Galéria'], 'udaje' => ['udaje.php', 'Údaje a zálohy'], 'nastavenia' => ['nastavenia.php', 'Nastavenia']];
    ?><!DOCTYPE html>
<html lang="sk"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex">
<title><?= e($title) ?> | <?= e($C['salon']['name']) ?></title>
<link rel="stylesheet" href="../assets/css/style.css?v=14">
<link rel="stylesheet" href="../assets/css/fonts.css?v=1"></head>
<body class="admin-body">
<div class="admin-wrap">
  <div class="admin-top">
    <h1><?= e($title) ?> <?= $badge ?></h1>
    <div><a class="btn btn-sm btn-ghost" href="../index.php" target="_blank">Zobraziť web</a> <a class="btn btn-sm" href="?logout=1">Odhlásiť</a></div>
  </div>
  <nav class="admin-tabs">
    <?php foreach ($tabs as $k => [$href, $label]): ?><a href="<?= $href ?>" class="<?= $active === $k ? 'on' : '' ?>"><?= $label ?></a><?php endforeach; ?>
  </nav>
  <?php if ($intro !== ''): ?><p class="admin-intro"><?= $intro ?></p><?php endif; ?>
  <?php if (!empty($_SESSION['flash'])): [$m, $t] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert <?= $t === 'ok' ? 'ok' : 'bad' ?>"><?= e($m) ?></div>
  <?php endif;
}

function admin_foot(): void { ?>
</div>
<script>
/* Náhľad fotky pri výbere z knižnice */
document.querySelectorAll('select[data-preview]').forEach(function (sel) {
  var img = document.getElementById(sel.dataset.preview);
  function upd() { if (img) { img.style.visibility = sel.value ? '' : 'hidden'; img.src = sel.value ? '../assets/img/' + sel.value : 'data:,'; } }
  sel.addEventListener('change', upd); upd();
});
/* Potvrdenie nebezpečných akcií */
document.querySelectorAll('[data-confirm]').forEach(function (el) {
  el.addEventListener('click', function (e) { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
</body></html>
<?php }

const ICONS = ['foot' => 'Chodidlo', 'polish' => 'Lak', 'heel' => 'Päta', 'nail' => 'Necht', 'dot' => 'Bodka (bradavice)', 'brace' => 'Špona', 'leaf' => 'Lístok', 'heart' => 'Srdce', 'sparkle' => 'Iskra', 'shield' => 'Štít'];

/** Uloží kompletný zoznam služieb (poradie = poradie v poli). */
function save_services(array $services): void {
    settings_update(function (array $s) use ($services) { $s['services'] = $services; return $s; });
}

/** Výber fotky z knižnice s náhľadom. $current môže byť aj starý zápis bez prípony. */
function image_picker(string $name, string $current, string $id, bool $allowEmpty = false): string {
    $cur = $current === '' ? '' : img_file($current);
    $list = media_list();
    if ($cur !== '' && !in_array($cur, $list, true)) array_unshift($list, $cur);
    $o = $allowEmpty ? '<option value=""' . ($cur === '' ? ' selected' : '') . '>— zástupný rámik „Vaša fotka“ —</option>' : '';
    foreach ($list as $f) $o .= '<option value="' . e($f) . '"' . ($f === $cur ? ' selected' : '') . '>' . e($f) . '</option>';
    return '<div class="img-pick"><img id="' . e($id) . '" src="../assets/img/' . e($cur) . '" alt=""' . ($cur === '' ? ' style="visibility:hidden"' : '') . '><select name="' . e($name) . '" data-preview="' . e($id) . '">' . $o . '</select></div>';
}

/** Slug z textu (bez diakritiky) pre identifikátory služieb a kategórií. */
function slugify(string $s): string {
    $s = strtr(mb_strtolower($s, 'UTF-8'), ['á' => 'a', 'ä' => 'a', 'č' => 'c', 'ď' => 'd', 'é' => 'e', 'ě' => 'e', 'í' => 'i', 'ĺ' => 'l', 'ľ' => 'l', 'ň' => 'n', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o', 'ŕ' => 'r', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ú' => 'u', 'ů' => 'u', 'ü' => 'u', 'ý' => 'y', 'ž' => 'z']);
    $s = trim(preg_replace('/[^a-z0-9]+/', '-', $s), '-');
    return $s === '' ? 'polozka' : (ctype_digit($s) ? 's-' . $s : $s);
}

/**
 * Spracuje nahraný obrázok: overí ho, zmenší (max 1600 px) a uloží ako JPEG do assets/img/uploads/.
 * Vráti relatívnu cestu (uploads/xyz.jpg) alebo reťazec s chybou v druhom prvku.
 * @return array{0:?string,1:string}
 */
function save_upload(array $f, string $baseName = ''): array {
    if (($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return [null, ''];
    if ($f['error'] !== UPLOAD_ERR_OK) return [null, 'Súbor sa nepodarilo nahrať (príliš veľký alebo chyba prenosu).'];
    if ($f['size'] > 15 * 1024 * 1024) return [null, 'Súbor je väčší ako 15 MB.'];
    $info = @getimagesize($f['tmp_name']);
    if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) return [null, 'Povolené sú len obrázky JPG, PNG alebo WebP.'];
    $src = @imagecreatefromstring((string)file_get_contents($f['tmp_name']));
    if (!$src) return [null, 'Obrázok sa nepodarilo načítať.'];
    // Otočenie podľa EXIF (fotky z mobilu)
    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $ex = @exif_read_data($f['tmp_name']);
        $rot = [3 => 180, 6 => -90, 8 => 90][$ex['Orientation'] ?? 1] ?? 0;
        if ($rot && ($r = imagerotate($src, $rot, 0))) $src = $r;
    }
    $w = imagesx($src); $h = imagesy($src);
    $scale = min(1, 1600 / max($w, $h));
    $nw = max(1, (int)round($w * $scale)); $nh = max(1, (int)round($h * $scale));
    $dst = imagecreatetruecolor($nw, $nh);
    imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));   // priehľadnosť PNG → biele pozadie
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0775, true);
    $name = slugify($baseName !== '' ? $baseName : pathinfo($f['name'], PATHINFO_FILENAME));
    $name = substr($name, 0, 40) . '-' . bin2hex(random_bytes(3)) . '.jpg';
    if (!imagejpeg($dst, UPLOAD_DIR . '/' . $name, 85)) return [null, 'Obrázok sa nepodarilo uložiť.'];
    return ['uploads/' . $name, ''];
}

/** Používa sa fotka niekde (služby, texty, stránky, galéria)? */
function media_in_use(string $file): bool {
    foreach ([SETTINGS_FILE, TEXTS_FILE, PAGES_FILE, GALLERY_FILE] as $p) {
        $raw = (string)@file_get_contents($p);
        if ($raw !== '' && str_contains($raw, json_encode($file, JSON_UNESCAPED_SLASHES))) return true;
    }
    return false;
}

/** Zmaže nahranú fotku aj jej náhľad. */
function delete_upload(string $file): void {
    @unlink(UPLOAD_DIR . '/' . basename($file));
    @unlink(__DIR__ . '/../assets/img/' . thumb_name($file));
}
