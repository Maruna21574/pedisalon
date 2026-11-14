<?php
http_response_code(404);
require_once __DIR__ . '/inc/lib.php';
$active = '';
$title = tx('e404.title');
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero" style="min-height:80vh;display:grid;place-items:center">
  <div class="wrap">
    <h1 style="font-size:clamp(5rem,16vw,10rem);color:var(--rose-500)">404</h1>
    <p><?= t('e404.text') ?></p>
    <p style="margin-top:28px"><a class="btn" href="index.php"><?= t('e404.btn1') ?></a> <a class="btn btn-ghost" href="rezervacia.php"><?= t('e404.btn2') ?></a></p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
