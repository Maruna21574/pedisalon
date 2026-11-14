<?php
/** Podstránka služby pridanej v administrácii: sluzba.php?id=<identifikátor> */
require_once __DIR__ . '/inc/lib.php';
$id = (string)($_GET['id'] ?? '');
if (!isset(cfg()['services'][$id])) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}
$pageKey = 'svc:' . $id;
require __DIR__ . '/inc/service-page.php';
