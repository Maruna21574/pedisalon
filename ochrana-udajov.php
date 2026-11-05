<?php
$active = '';
$title = 'Ochrana osobných údajov';
$desc = 'Zásady spracovania osobných údajov PediSalónu ZK.';
require __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="wrap">
    <nav class="crumbs"><a href="index.php">Domov</a> › <span>Ochrana osobných údajov</span></nav>
    <h1>Ochrana osobných údajov</h1>
  </div>
</section>
<section class="section">
  <div class="wrap prose" style="max-width:820px">
    <p><em>Vzor – pred zverejnením ho nechajte skontrolovať a doplňte údaje prevádzkovateľa.</em></p>
    <h2>Prevádzkovateľ</h2>
    <p><?= e($S['name']) ?>, <?= e($S['address']) ?>, e-mail: <?= e($S['email']) ?>, tel.: <?= e($S['phone']) ?>. <?= e($S['ico']) ?></p>
    <h2>Aké údaje spracúvame a prečo</h2>
    <p>Pri online rezervácii a kontaktnom formulári spracúvame meno, telefónne číslo, e-mail a poznámku, ktorú nám uvediete. Údaje používame výlučne na vybavenie rezervácie, komunikáciu s vami a organizáciu prevádzky salóna.</p>
    <h2>Právny základ a doba uchovávania</h2>
    <p>Spracúvanie je nevyhnutné na plnenie zmluvy (rezervácia služby) a na základe vášho súhlasu. Údaje o rezervácii uchovávame po dobu nevyhnutnú na poskytnutie služby, najviac 12 mesiacov, pokiaľ právny predpis neurčuje inak.</p>
    <h2>Komu údaje sprístupňujeme</h2>
    <p>Údaje nepredávame ani neposkytujeme tretím stranám. Technicky ich spracúva poskytovateľ webhostingu.</p>
    <h2>Vaše práva</h2>
    <p>Máte právo na prístup k údajom, ich opravu, vymazanie, obmedzenie spracúvania, prenosnosť a právo namietať. Kedykoľvek môžete odvolať súhlas. Žiadosti posielajte na <?= e($S['email']) ?>. Máte tiež právo podať sťažnosť Úradu na ochranu osobných údajov SR.</p>
    <h2>Cookies a externé služby</h2>
    <p>Web nepoužíva marketingové ani analytické cookies. Písma sa načítavajú zo služby Google Fonts a 3D animácia z verejnej knižnice cdnjs.</p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
