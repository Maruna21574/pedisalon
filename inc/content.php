<?php
/**
 * Obsah upraviteľný v administrácii: certifikáty a témy Poradne.
 * Uložené v data/certificates.json a data/poradna.json; kým ich majiteľka neuloží, použijú sa predvolené hodnoty.
 */

define('CERTS_FILE', DATA_DIR . '/certificates.json');
define('PORADNA_FILE', DATA_DIR . '/poradna.json');

/** Certifikáty: [['file' (voči assets/img), 'title', 'date'], ...]. */
function certificates_data(): array {
    $d = store_read(CERTS_FILE);
    if (isset($d['items']) && is_array($d['items'])) return $d['items'];
    $c = fn(string $f, string $t, string $d) => ['file' => 'certifikaty/' . $f, 'title' => $t, 'date' => $d];
    return [
        $c('cert-podofix-spony.jpg',        'Špony Podofix – ošetrenie zarastených nechtov',             '5. 9. 2025'),
        $c('cert-zhrubnute-nechty.jpg',     'Ošetrenie zhrubnutých nechtov',                             '5. 9. 2025'),
        $c('cert-estetika-pedikura.jpg',    'Dokonalá estetická pedikúra – aplikácia géllaku',           '23. 4. 2025'),
        $c('cert-pristrojova-pedikura.jpg', 'Prístrojová pedikúra – brúska a frézy (8 hodín)',           '15. 4. 2025'),
        $c('cert-frezky.jpg',               'Frézky v pedikérskej praxi (online školenie)',              '28. 12. 2025'),
        $c('cert-reflexna-masaz.jpg',       'Reflexná masáž chodidiel',                                  '18. 2. 2026'),
        $c('cert-pedikersky-workshop.jpg',  'Pedikérsky workshop, Liptovský Mikuláš',                    '27. 9. 2026'),
        $c('cert-podopharm-seminar.jpg',    'Veľký Podopharm seminár, Nitra',                            '15. 5. 2026'),
        $c('cert-pharm-foot.jpg',           'Pharm Foot – produkty v terapii vírusových bradavíc (webinár)', '24. 9. 2025'),
        $c('cert-titanova-nit.jpg',         'Titánová niť NITI – školenie',                              '17. 11. 2026'),
        $c('cert-mykoticke-ochorenia.jpg',  'Mykotické ochorenia na nechtoch a koži',                    '4. 11. 2026'),
    ];
}

/** Témy Poradne: [['id','icon','h','intro','causes'[],'signs'[],'help','link'=>[stránka,popis],'doctor'], ...]. */
function poradna_data(): array {
    $d = store_read(PORADNA_FILE);
    if (isset($d['topics']) && is_array($d['topics'])) return $d['topics'];
    return require __DIR__ . '/poradna-default.php';
}
