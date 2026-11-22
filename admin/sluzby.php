<?php
require __DIR__ . '/_boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (($_POST['action'] ?? '') === 'reset_defaults') {
        // Obnovenie zoznamu služieb a cien podľa cenníka z 1. 4. 2026 (predvolené hodnoty v config.php)
        settings_update(function (array $s) { unset($s['services']); return $s; });
        store_mutate(PAGES_FILE, function (array $all) { foreach (['pedikura.php', 'mykoza.php', 'bradavice-kurie-oka.php', 'brusenie-paty.php'] as $k) unset($all[$k]); return ['data' => $all]; });
        flash('Zoznam služieb a ceny boli obnovené podľa aktuálneho cenníka (platného od 1. 4. 2026).');
        redirect('sluzby.php');
    }
    $id = (string)($_POST['id'] ?? '');
    $dir = $_POST['dir'] ?? '';
    $services = $C['services'];
    if (isset($services[$id]) && in_array($dir, ['up', 'down'], true)) {
        $ids = array_keys($services);
        $i = array_search($id, $ids, true);
        $j = $dir === 'up' ? $i - 1 : $i + 1;
        if (isset($ids[$j])) {
            [$ids[$i], $ids[$j]] = [$ids[$j], $ids[$i]];
            $sorted = [];
            foreach ($ids as $k) $sorted[$k] = $services[$k];
            save_services($sorted);
        }
    }
    redirect('sluzby.php');
}

admin_head('Služby a ceny', 'sluzby', '', 'Kliknutím na <b>Upraviť</b> otvoríte všetko k jednej službe: názov, cenu, popis, fotky aj texty jej podstránky. Poradie zmeníte šípkami.');
$seen = [];
?>
<div class="panel">
  <div class="panel-head">
    <h2>Zoznam služieb (<?= count($C['services']) ?>)</h2>
    <a class="btn btn-sm" href="sluzba-edit.php?new=1">+ Pridať novú službu</a>
  </div>
  <table class="tbl svc-list">
    <thead><tr><th>Fotka</th><th>Služba</th><th>Kategória</th><th>Cena</th><th>Trvanie</th><th>Podstránka</th><th>Poradie</th><th></th></tr></thead>
    <tbody>
    <?php $n = 0; $total = count($C['services']); foreach ($C['services'] as $id => $s): $n++; $id = (string)$id; ?>
      <tr>
        <td><img class="thumb-sm" src="../assets/img/<?= e(img_file($s['img'])) ?>" alt=""></td>
        <td><b><?= e($s['name']) ?></b><br><small style="color:var(--muted)"><?= e(mb_strimwidth($s['short'], 0, 70, '…')) ?></small></td>
        <td><?= e($s['category'] ?? 'Ostatné') ?></td>
        <td><b><?= e(money($s['price'])) ?></b></td>
        <td><?= (int)$s['duration'] ?> min</td>
        <td><a href="../<?= e($s['page']) ?>" target="_blank">Zobraziť</a><?php if (isset($seen[$s['page']])): ?><br><small style="color:var(--muted)">spoločná so službou „<?= e($C['services'][$seen[$s['page']]]['name']) ?>“</small><?php endif; $seen[$s['page']] ??= $id; ?></td>
        <td>
          <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($id) ?>">
            <button class="mini" name="dir" value="up" title="Posunúť vyššie" <?= $n === 1 ? 'disabled' : '' ?>>▲</button>
            <button class="mini" name="dir" value="down" title="Posunúť nižšie" <?= $n === $total ? 'disabled' : '' ?>>▼</button>
          </form>
        </td>
        <td><a class="btn btn-sm" href="sluzba-edit.php?id=<?= urlencode($id) ?>">Upraviť</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="panel hint">
  <form method="post" onsubmit="return confirm('Naozaj obnoviť zoznam služieb a ceny podľa cenníka z 1. 4. 2026? Vaše doterajšie úpravy zoznamu služieb a podstránok Pedikúra, Ošetrenie nechtov a Kurie oká sa prepíšu.');">
    <?= csrf_field() ?><input type="hidden" name="action" value="reset_defaults">
    <p style="margin:0 0 10px"><b>Cenník platný od 1. 4. 2026.</b> Ak sa vám tu zobrazujú staré služby (napr. Brúsenie päty), obnovte zoznam podľa nového cenníka. Ceny a trvanie potom môžete ďalej upravovať ručne – tlačidlom <b>Upraviť</b> pri každej službe.</p>
    <button class="btn btn-sm" type="submit">Obnoviť službu a ceny podľa nového cenníka</button>
  </form>
</div>
<div class="panel hint">
  <p style="margin:0"><b>Spoločné texty podstránok služieb</b> (nadpisy „O službe“, „Čo zahŕňa“, tlačidlo „Rezervovať“ a pod.) upravíte v sekcii
  <a href="texty.php#sec-<?= slugify('Služby a cenník Podstránky služieb (spoločné nadpisy)') ?>">Texty webu → Služby a cenník</a>.</p>
</div>
<?php admin_foot();
