<h2>Kliento dashboard</h2><a class="btn" href="<?= url('project.new') ?>">Naujas projektas</a>
<table><tr><th>ID</th><th>Pavadinimas</th><th>Statusas</th><th></th></tr>
<?php foreach($projects as $p): ?><tr><td><?= (int)$p['id'] ?></td><td><?= e($p['title']) ?></td><td><span class="badge"><?= e($p['status']) ?></span></td><td><a href="<?= url('project.view') ?>&id=<?= (int)$p['id'] ?>">Atidaryti</a></td></tr><?php endforeach; ?>
</table>
