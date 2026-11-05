<?php
require_once __DIR__ . '/lib.php';
$C = cfg(); $S = $C['salon'];
$pageTitle = isset($title) ? $title . ' | ' . $S['name'] : $S['name'] . ' – ' . $S['tagline'];
$pageDesc  = $desc ?? 'Profesionálna pedikúra, brúsenie päty, ošetrenie mykózy, bradavíc a vrastajúcich nechtov. Objednajte sa online v PediSalóne ZK.';
$active = $active ?? '';
$isHome = ($active === 'home');

function icon(string $n, string $cls = 'ico'): string {
    $p = [
        'foot'   => '<path d="M9 5.2c0-1.4.9-2.4 2-2.4s2 1 2 2.4M14.5 4.6c.9-.4 1.9.1 2.2 1.1M17.7 7.2c.8-.2 1.5.4 1.6 1.2"/><path d="M8.5 9.5c-1.7 2.6-2.2 6-1.2 9 .5 1.6 2 2.7 3.8 2.7 2.2 0 3.7-1.5 4-3.5.4-2.6-.6-4.5-1.3-6.5-.9-2.6-4-3.2-5.3-1.7z"/>',
        'polish' => '<rect x="8" y="11" width="8" height="10" rx="2"/><path d="M10 11V8h4v3M11 8V3h2v5"/>',
        'heel'   => '<path d="M6 20c0-3 1-5 2-8s0-6 3-8c3-1.5 6 .5 6 4 0 4-2 6-2 9v3"/><path d="M5 20h12"/>',
        'nail'   => '<path d="M7 20V9c0-3 2-5 5-5s5 2 5 5v11"/><path d="M7 14h10M9 20v-3M15 20v-3"/>',
        'dot'    => '<circle cx="12" cy="12" r="3"/><circle cx="12" cy="12" r="8"/>',
        'brace'  => '<path d="M5 15c2 3 12 3 14 0"/><path d="M8 15V8m8 7V8M8 11h8"/><circle cx="12" cy="6" r="2"/>',
        'leaf'   => '<path d="M5 19C5 10 10 5 19 5c0 9-5 14-14 14z"/><path d="M5 19c3-5 6-8 10-10"/>',
        'phone'  => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'pin'    => '<path d="M12 21s7-6 7-11a7 7 0 0 0-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'clock'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'mail'   => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'check'  => '<path d="m5 12 5 5 9-10"/>',
        'arrow'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'heart'  => '<path d="M12 20s-8-5-8-11a4.5 4.5 0 0 1 8-2.5A4.5 4.5 0 0 1 20 9c0 6-8 11-8 11z"/>',
        'shield' => '<path d="M12 3 5 6v6c0 4 3 7 7 9 4-2 7-5 7-9V6z"/><path d="m9 12 2 2 4-4"/>',
        'sparkle'=> '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>',
        'fb'     => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/>',
        'ig'     => '<rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="3.7"/><circle cx="17" cy="7" r=".8"/>',
    ];
    return '<svg class="' . $cls . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$n] ?? '') . '</svg>';
}

$nav = [
    ['home',   'index.php',     'Domov'],
    ['sluzby', 'sluzby.php',    'Služby'],
    ['cennik', 'cennik.php',    'Cenník'],
    ['galeria','galeria.php',   'Galéria'],
    ['onas',   'o-nas.php',     'O nás'],
    ['kontakt','kontakt.php',   'Kontakt'],
];
$subnav = [];
foreach ($C['services'] as $s) { $subnav[$s['page']] = $s['page']; }
$serviceMenu = [
    ['pedikura.php', 'Pedikúra'],
    ['brusenie-paty.php', 'Brúsenie päty'],
    ['mykoza.php', 'Mykóza nechtov'],
    ['bradavice-kurie-oka.php', 'Bradavice a kurie oká'],
    ['spona.php', 'Špona – vrastajúci necht'],
];
?><!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($pageDesc) ?>">
<meta name="theme-color" content="#cbd9c9">
<meta property="og:title" content="<?= e($pageTitle) ?>">
<meta property="og:description" content="<?= e($pageDesc) ?>">
<meta property="og:image" content="assets/img/logo.jpg">
<meta property="og:type" content="website">
<link rel="icon" href="assets/img/logo.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Dancing+Script:wght@600&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css?v=3">
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org', '@type' => 'HealthAndBeautyBusiness',
    'name' => $S['name'], 'telephone' => $S['phone'], 'email' => $S['email'],
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => $S['address']],
    'image' => 'assets/img/logo.jpg',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
</head>
<body class="<?= trim(($isHome ? 'is-home ' : '') . ($bodyClass ?? '')) ?>">
<script>document.body.classList.add('entering')</script>
<noscript><style>.reveal{opacity:1!important;transform:none!important}.page-curtain{display:none}</style></noscript>
<div class="page-curtain" aria-hidden="true"></div>
<a class="skip" href="#obsah">Preskočiť na obsah</a>

<header class="site-header" id="top">
  <div class="wrap bar">
    <a class="brand" href="index.php" aria-label="<?= e($S['name']) ?> – domov">
      <img src="assets/img/logo.jpg" alt="" width="52" height="52">
      <span class="brand-text"><b>PediSalón</b> <i>ZK</i></span>
    </a>
    <nav class="main-nav" id="mainNav" aria-label="Hlavná navigácia">
      <?php foreach ($nav as [$key, $href, $label]): ?>
        <?php if ($key === 'sluzby'): ?>
          <div class="has-sub <?= in_array($active, ['sluzby','pedikura','brusenie','mykoza','bradavice','spona']) ? 'active' : '' ?>">
            <a href="<?= $href ?>"><?= $label ?> <svg viewBox="0 0 10 6" class="chev" aria-hidden="true"><path d="m1 1 4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></a>
            <div class="sub">
              <?php foreach ($serviceMenu as [$h, $l]): ?><a href="<?= $h ?>"><?= $l ?></a><?php endforeach; ?>
              <a href="sluzby.php" class="all">Všetky služby →</a>
            </div>
          </div>
        <?php else: ?>
          <a href="<?= $href ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= $label ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
      <a href="rezervacia.php" class="btn btn-sm nav-cta">Rezervovať termín</a>
    </nav>
    <button class="burger" id="burger" aria-label="Menu" aria-expanded="false" aria-controls="mainNav"><span></span><span></span><span></span></button>
  </div>
</header>

<main id="obsah">
