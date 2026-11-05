<?php
http_response_code(404);
$active = '';
$title = 'Stránka sa nenašla';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero" style="min-height:80vh;display:grid;place-items:center">
  <div class="wrap">
    <h1 style="font-size:clamp(5rem,16vw,10rem);color:var(--rose-500)">404</h1>
    <p>Táto stránka sa stratila ako ponožka v práčke. Vráťte sa na úvod alebo sa objednajte.</p>
    <p style="margin-top:28px"><a class="btn" href="index.php">Na úvodnú stránku</a> <a class="btn btn-ghost" href="rezervacia.php">Rezervácia</a></p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
