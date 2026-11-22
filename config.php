<?php
/**
 * PediSalón ZK – centrálna konfigurácia.
 * Predvolené hodnoty (kontakty, hodiny, služby, ceny). Všetko je možné meniť v administrácii
 * (/admin/) – zmeny sa ukladajú do data/settings.json a majú prednosť pred týmto súborom.
 */

date_default_timezone_set('Europe/Bratislava');

return [
    // ---------- Základné údaje salónu (UPRAVTE) ----------
    'salon' => [
        'name'      => 'PediSalón ZK',
        'tagline'   => 'Starostlivosť o vaše nohy s láskou',
        'phone'     => '+421 944 727 573',
        'phone_raw' => '+421944727573',
        'email'     => 'info@pedisalonzk.sk',      // sem chodia upozornenia o rezerváciách
        'address'   => 'Nám. baníkov 2, 972 51 Handlová',
        'city'      => 'Handlová',
        'facebook'  => 'https://www.facebook.com/972049906001172/',
        'instagram' => 'https://www.instagram.com/pedisalonzk/',
        'maps_embed'=> 'https://www.google.com/maps?q=N%C3%A1m.+ban%C3%ADkov+2%2C+972+51+Handlov%C3%A1%2C+Slovakia&output=embed',   // adresu mapy zmeníte v administrácii (Nastavenia)
        'ico'       => '',   // IČO / fakturačné údaje (voliteľné, zobrazí sa v pätičke)
    ],

    // ---------- Otváracie hodiny ----------
    // Kľúč = deň v týždni (1 = pondelok … 7 = nedeľa). null = zatvorené.
    'hours' => [
        1 => ['09:00', '17:00'],
        2 => ['09:00', '17:00'],
        3 => ['09:00', '17:00'],
        4 => ['09:00', '17:00'],
        5 => ['09:00', '15:00'],
        6 => null,
        7 => null,
    ],

    // ---------- Rezervácie ----------
    'booking' => [
        'slot_step'     => 30,   // krok začiatkov rezervácií v minútach
        'min_lead_hours'=> 3,    // najskôr o koľko hodín odo teraz sa dá rezervovať
        'max_days_ahead'=> 60,   // na koľko dní dopredu
        'auto_confirm'  => false, // true = rezervácia je ihneď potvrdená, false = čaká na potvrdenie salónom
        'buffer_min'    => 10,   // prestávka medzi klientmi (min)
    ],

    // ---------- Administrácia ----------
    // Heslo sa mení v administrácii: /admin/ → Nastavenia → Zmena hesla (uloží sa do data/settings.json
    // a má prednosť pred touto hodnotou). Hash pre núdzový reset: php -r "echo password_hash('heslo', PASSWORD_DEFAULT);"
    'admin_password_hash' => '$2y$12$34tZ0NqvJJ2ACtCHMGosSOk5XCNAYwhsxnZbIymB3e29oUcK8IfZa',

    // ---------- Služby a ceny (podľa cenníka platného od 1. 4. 2026) ----------
    'services' => [
        'pedikura-sucha' => [
            'category' => 'Pedikúra',
            'name'     => 'Pedikúra suchá (prístrojová)',
            'short'    => 'Dezinfekcia, ošetrenie zhrubnutej kože frézkou, úprava nechtov, krém.',
            'duration' => 60,
            'price'    => 25,
            'page'     => 'pedikura.php',
            'menu'     => 'Pedikúra a lakovanie',
            'img'      => 'pedikura-1.jpg',
            'icon'     => 'foot',
        ],
        'pedikura-mokra' => [
            'category' => 'Pedikúra',
            'name'     => 'Pedikúra mokrá (skalpelová)',
            'short'    => 'Dezinfekcia, vodný kúpeľ 10–15 min., odstránenie zhrubnutej kože skalpelom, úprava nechtov, krém.',
            'duration' => 60,
            'price'    => 25,
            'page'     => 'pedikura.php',
            'img'      => 'pedikura-3.jpg',
            'icon'     => 'foot',
        ],
        'pedikura-kombinovana' => [
            'category' => 'Pedikúra',
            'name'     => 'Pedikúra kombinovaná',
            'short'    => 'Dezinfekcia, vodný kúpeľ 5 min., ošetrenie zhrubnutej kože skalpelom a frézkou, úprava nechtov, krém.',
            'duration' => 60,
            'price'    => 25,
            'page'     => 'pedikura.php',
            'img'      => 'pedikura-5.jpg',
            'icon'     => 'foot',
        ],
        'pedikura-gellak' => [
            'category' => 'Pedikúra',
            'name'     => 'Pedikúra + Gellak',
            'short'    => 'Pedikúra podľa výberu, Gellak, olejček.',
            'duration' => 90,
            'price'    => 30,
            'page'     => 'pedikura.php',
            'img'      => 'pedikura-7.jpg',
            'icon'     => 'polish',
        ],
        'pedikura-moyra' => [
            'category' => 'Pedikúra',
            'name'     => 'Pedikúra + Lak Moyra',
            'short'    => 'Pedikúra podľa výberu, Lak Moyra, olejček.',
            'duration' => 90,
            'price'    => 30,
            'page'     => 'pedikura.php',
            'img'      => 'pedikura-9.jpg',
            'icon'     => 'polish',
        ],
        'lakovanie-gellak' => [
            'category' => 'Lakovanie',
            'name'     => 'Lakovanie bez pedikúry – Gellak',
            'short'    => 'Úprava nechtov, Gellak, olejček.',
            'duration' => 60,
            'price'    => 23,
            'page'     => 'pedikura.php',
            'img'      => 'pedikura-10.jpg',
            'icon'     => 'polish',
        ],
        'lakovanie-moyra' => [
            'category' => 'Lakovanie',
            'name'     => 'Lakovanie bez pedikúry – Lak Moyra',
            'short'    => 'Úprava nechtov, Lak Moyra, olejček.',
            'duration' => 50,
            'price'    => 23,
            'page'     => 'pedikura.php',
            'img'      => 'pedikura-2.jpg',
            'icon'     => 'polish',
        ],
        'kupel-peeling-maska' => [
            'category' => 'Relax a masáž',
            'name'     => 'Kúpeľ, peeling, maska',
            'short'    => 'Relaxačný kúpeľ, peeling, maska, krém.',
            'duration' => 30,
            'price'    => 20,
            'page'     => 'pedikura.php',
            'img'      => 'salon-cakaren.jpg',
            'icon'     => 'leaf',
        ],
        'reflexna-masaz' => [
            'category' => 'Relax a masáž',
            'name'     => 'Reflexná masáž chodidiel',
            'short'    => 'Reflexná masáž chodidiel, 30 minút.',
            'duration' => 30,
            'price'    => 20,
            'page'     => 'sluzba.php?id=reflexna-masaz',
            'menu'     => 'Reflexná masáž',
            'img'      => 'salon-pracovisko-2.jpg',
            'icon'     => 'heart',
        ],
        'kurie-oko' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Ošetrenie kuriecho oka, otlaku',
            'short'    => 'Dezinfekcia, odstránenie kuriho oka, otlaku.',
            'duration' => 20,
            'price'    => 12,
            'page'     => 'bradavice-kurie-oka.php',
            'menu'     => 'Kurie oká a otlaky',
            'img'      => 'bradavice-2.jpg',
            'icon'     => 'dot',
        ],
        'kurie-oko-pedikura' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Ošetrenie kuriecho oka, otlaku popri pedikúre',
            'short'    => 'Dezinfekcia, odstránenie kuriho oka, otlaku – popri pedikúre.',
            'duration' => 10,
            'price'    => 5,
            'page'     => 'bradavice-kurie-oka.php',
            'img'      => 'bradavice-1.jpg',
            'icon'     => 'dot',
        ],
        'poskodeny-necht' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Ošetrenie poškodeného nechtu',
            'short'    => 'Dezinfekcia, zbrúsenie nechtovej platničky.',
            'duration' => 20,
            'price'    => 12,
            'page'     => 'mykoza.php',
            'menu'     => 'Ošetrenie nechtov',
            'img'      => 'mykoza-1.jpg',
            'icon'     => 'nail',
        ],
        'poskodeny-necht-pedikura' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Ošetrenie poškodeného nechtu popri pedikúre',
            'short'    => 'Dezinfekcia, zbrúsenie nechtovej platničky – popri pedikúre.',
            'duration' => 10,
            'price'    => 5,
            'page'     => 'mykoza.php',
            'img'      => 'mykoza-2.jpg',
            'icon'     => 'nail',
        ],
        'zarasteny-necht' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Ošetrenie zarasteného nechtu bez pedikúry',
            'short'    => 'Dezinfekcia, odstránenie zarastených častí nechtu.',
            'duration' => 20,
            'price'    => 12,
            'page'     => 'mykoza.php',
            'img'      => 'spona-2.jpg',
            'icon'     => 'nail',
        ],
        'zarasteny-necht-pedikura' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Ošetrenie zarasteného nechtu popri pedikúre',
            'short'    => 'Dezinfekcia, odstránenie zarastených častí nechtu – popri pedikúre.',
            'duration' => 10,
            'price'    => 5,
            'page'     => 'mykoza.php',
            'img'      => 'spona-3.jpg',
            'icon'     => 'nail',
        ],
        'protetika-nechtu' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Protetika (náhrada) nechtu',
            'short'    => 'Dezinfekcia, príprava nechtovej platničky, nanesenie gélu, tvarovanie nechtu.',
            'duration' => 45,
            'price'    => 23,
            'page'     => 'mykoza.php',
            'img'      => 'spona-4.jpg',
            'icon'     => 'nail',
        ],
        'tamponada' => [
            'category' => 'Ošetrenie nechtov a kože',
            'name'     => 'Tamponáda',
            'short'    => 'Čistenie nechtu, zavedenie tamponády.',
            'duration' => 15,
            'price'    => 2,
            'page'     => 'mykoza.php',
            'img'      => 'spona-5.jpg',
            'icon'     => 'nail',
        ],
        'spona' => [
            'category' => 'Špony',
            'name'     => 'Aplikácia špony Podofix',
            'short'    => 'Neinvazívna korekcia vrastajúceho nechta pomocou špony Podofix.',
            'duration' => 45,
            'price'    => 30,
            'page'     => 'spona.php',
            'menu'     => 'Špona – vrastajúci necht',
            'img'      => 'spona-1.jpg',
            'icon'     => 'brace',
        ],
    ],
];
