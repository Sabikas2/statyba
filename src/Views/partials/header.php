<!doctype html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statyba Platforma</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f6f8fb; color: #1f2937; }
        .wrap { max-width: 1080px; margin: 0 auto; padding: 20px; }
        nav { background:#111827; color:#fff; }
        nav .wrap { display:flex; justify-content:space-between; align-items:center; }
        a { color:#2563eb; text-decoration:none; }
        .card { background:#fff; border-radius:12px; padding:16px; margin-bottom:16px; box-shadow:0 2px 6px rgba(0,0,0,.08); }
        input, select, textarea, button { width:100%; padding:10px; margin-top:8px; margin-bottom:10px; border-radius:8px; border:1px solid #d1d5db; }
        button { background:#2563eb; color:#fff; cursor:pointer; border:none; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:16px; }
        .flash{padding:10px;background:#fee2e2;border-radius:8px;margin:10px 0;color:#991b1b;}
    </style>
</head>
<body>
<nav>
    <div class="wrap">
        <strong>STATYBA PRO</strong>
        <div>
            <a href="<?= \App\Core\Url::to('/') ?>" style="color:white;margin-right:10px;">Pagrindinis</a>
            <?php if (!empty($_SESSION['user'])): ?>
                <a href="<?= \App\Core\Url::route('logout') ?>" style="color:white;">Atsijungti</a>
            <?php else: ?>
                <a href="<?= \App\Core\Url::route('login') ?>" style="color:white;margin-right:10px;">Prisijungti</a>
                <a href="<?= \App\Core\Url::route('register') ?>" style="color:white;">Registruotis</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="wrap">
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
