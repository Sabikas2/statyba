<!doctype html><html lang="lt"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e(config('app_name','BuildMatch AI')) ?></title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><div class="container"><a href="<?= url('home') ?>" class="logo">BuildMatch AI</a><nav>
<a href="<?= url('home') ?>">Home</a>
<?php if(current_user()): ?>
<a href="<?= url(current_user()['role'].'.dashboard') ?>">Dashboard</a>
<a href="<?= url('logout') ?>">Atsijungti</a>
<?php else: ?>
<a href="<?= url('login') ?>">Prisijungti</a><a href="<?= url('register') ?>">Registracija</a>
<?php endif; ?>
</nav></div></header><main class="container">
<?php if($m=flash('success')): ?><div class="flash ok"><?= e($m) ?></div><?php endif; ?>
<?php if($m=flash('error')): ?><div class="flash err"><?= e($m) ?></div><?php endif; ?>
