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
        'phone'     => '+421 900 000 000',
        'phone_raw' => '+421900000000',
        'email'     => 'info@pedisalon-zk.sk',      // sem chodia upozornenia o rezerváciách
        'address'   => 'Nám. baníkov 2, 972 51 Handlová',
        'city'      => 'Handlová',
        'facebook'  => 'https://www.facebook.com/',
        'instagram' => 'https://www.instagram.com/',
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

    // ---------- Služby (ceny sú orientačné – UPRAVTE podľa cenníka) ----------
    'services' => [
        'pedikura' => [
            'name'     => 'Kompletná pedikúra',
            'short'    => 'Kúpeľ, úprava nechtov, kutikúl a ošetrenie chodidiel.',
            'duration' => 60,
            'price'    => 25,
            'page'     => 'pedikura.php',
            'menu'     => 'Pedikúra',
            'img'      => 'pedikura-1.jpg',
            'icon'     => 'foot',
        ],
        'pedikura-gel' => [
            'name'     => 'Pedikúra s gél lakom',
            'short'    => 'Kompletná pedikúra + trvanlivý gél lak v odtieni podľa výberu.',
            'duration' => 80,
            'price'    => 32,
            'page'     => 'pedikura.php',
            'img'      => 'pedikura-5.jpg',
            'icon'     => 'polish',
        ],
        'brusenie' => [
            'name'     => 'Brúsenie päty a mozoľov',
            'short'    => 'Šetrné odstránenie zrohovatenej kože a ošetrenie popraskaných pätiek.',
            'duration' => 45,
            'price'    => 20,
            'page'     => 'brusenie-paty.php',
            'menu'     => 'Brúsenie päty',
            'img'      => 'brusenie-1.jpg',
            'icon'     => 'heel',
        ],
        'mykoza' => [
            'name'     => 'Ošetrenie nechtov postihnutých mykózou',
            'short'    => 'Šetrná úprava a ošetrenie zhrubnutých, zmenených nechtov.',
            'duration' => 60,
            'price'    => 30,
            'page'     => 'mykoza.php',
            'menu'     => 'Mykóza nechtov',
            'img'      => 'mykoza-1.jpg',
            'icon'     => 'nail',
        ],
        'bradavice' => [
            'name'     => 'Ošetrenie bradavíc a kurích očí',
            'short'    => 'Precízne a nebolestivé odstránenie kurích očí a ošetrenie problémových miest.',
            'duration' => 45,
            'price'    => 25,
            'page'     => 'bradavice-kurie-oka.php',
            'menu'     => 'Bradavice a kurie oká',
            'img'      => 'bradavice-2.jpg',
            'icon'     => 'dot',
        ],
        'spona' => [
            'name'     => 'Korekcia vrastajúceho nechta – špona',
            'short'    => 'Neinvazívna korekcia vrastajúceho nechta pomocou špony.',
            'duration' => 45,
            'price'    => 30,
            'page'     => 'spona.php',
            'menu'     => 'Špona – vrastajúci necht',
            'img'      => 'spona-1.jpg',
            'icon'     => 'brace',
        ],
    ],
];
