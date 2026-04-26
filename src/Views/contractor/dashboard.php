<h1>Rangovo panelė</h1>

<div class="grid">
    <div class="card">
        <h3>Profilio redagavimas</h3>
        <form method="post" action="<?= \App\Core\Url::route('contractor.profile.update') ?>">
            <input name="city" placeholder="Miestas" required>
            <input name="speciality" placeholder="Specializacija" required>
            <textarea name="profile_text" placeholder="Aprašykite savo patirtį" required></textarea>
            <button>Išsaugoti</button>
        </form>
    </div>

    <div class="card">
        <h3>Sukurti reklamos kampaniją (monetizacija)</h3>
        <form method="post" action="<?= \App\Core\Url::route('contractor.ad.create') ?>">
            <input name="title" placeholder="Reklamos pavadinimas" required>
            <textarea name="description" placeholder="Reklamos aprašymas" required></textarea>
            <input type="number" step="0.01" name="daily_budget" placeholder="Dienos biudžetas" required>
            <button>Pateikti tvirtinimui</button>
        </form>
    </div>
</div>

<div class="card">
    <h3>Gautos užklausos</h3>
    <?php foreach ($inquiries as $inq): ?>
        <div style="border-top:1px solid #e5e7eb;padding-top:10px;margin-top:10px;">
            <strong><?= htmlspecialchars($inq['project_title']) ?></strong> · Klientas: <?= htmlspecialchars($inq['client_name']) ?>
            <p><?= htmlspecialchars($inq['message']) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h3>Mano reklamos</h3>
    <?php foreach ($ads as $ad): ?>
        <div style="border-top:1px solid #e5e7eb;padding-top:10px;margin-top:10px;">
            <strong><?= htmlspecialchars($ad['title']) ?></strong> · Statusas: <?= htmlspecialchars($ad['status']) ?> · €<?= htmlspecialchars((string)$ad['daily_budget']) ?>/d.
        </div>
    <?php endforeach; ?>
</div>
