<div class="card">
    <h1>Vieša rangovų paieška</h1>
    <p>Šis puslapis pasiekiamas iš išorės, be prisijungimo.</p>

    <form method="get" action="<?= \App\Core\Url::to('/') ?>">
        <input type="hidden" name="route" value="contractor.search">
        <input name="q" placeholder="Raktažodis (pvz. stogai, fasadas)" value="<?= htmlspecialchars($q) ?>">
        <input name="city" placeholder="Miestas" value="<?= htmlspecialchars($city) ?>">
        <input name="speciality" placeholder="Specializacija" value="<?= htmlspecialchars($speciality) ?>">
        <button>Ieškoti rangovų</button>
    </form>
</div>

<div class="card">
    <h2>Rezultatai (<?= count($contractors) ?>)</h2>
    <?php if ($contractors === []): ?>
        <p>Pagal pasirinktus filtrus nieko nerasta.</p>
    <?php endif; ?>

    <?php foreach ($contractors as $contractor): ?>
        <div style="border-top:1px solid #e5e7eb;padding-top:10px;margin-top:10px;">
            <strong><?= htmlspecialchars($contractor['name']) ?></strong>
            <p><?= htmlspecialchars($contractor['city']) ?> · <?= htmlspecialchars($contractor['speciality']) ?></p>
            <p><?= htmlspecialchars($contractor['profile_text'] ?? '') ?></p>
            <a href="<?= \App\Core\Url::route('register') ?>">Siųsti užklausą (reikia kliento paskyros)</a>
        </div>
    <?php endforeach; ?>
</div>
