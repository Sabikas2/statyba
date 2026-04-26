<h2>Admin dashboard</h2>
<div class="cards"><?php foreach($stats as $k=>$v): ?><div class="card"><strong><?= e($k) ?></strong><div><?= (int)$v ?></div></div><?php endforeach; ?></div>
<p><a href="<?= url('admin.users') ?>">Vartotojai</a> | <a href="<?= url('admin.contractors') ?>">Rangovai</a> | <a href="<?= url('admin.projects') ?>">Projektai ir email logai</a> | <a href="<?= url('admin.import_contractors') ?>">CSV importas</a> | <a href="<?= url('admin.settings') ?>">Nustatymai</a></p>
