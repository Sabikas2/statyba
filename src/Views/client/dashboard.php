<h1>Kliento panelė</h1>

<div class="grid">
    <div class="card">
        <h3>Naujas projektas</h3>
        <form method="post" action="<?= \App\Core\Url::route('client.project.create') ?>">
            <input name="title" placeholder="Projekto pavadinimas" required>
            <textarea name="description" placeholder="Aprašymas" required></textarea>
            <input name="city" placeholder="Miestas" required>
            <input type="number" step="0.01" name="budget" placeholder="Biudžetas" required>
            <button>Publikuoti projektą</button>
        </form>
    </div>

    <div class="card">
        <h3>Rangovų paieška (automatinė)</h3>
        <form method="get" action="<?= \App\Core\Url::to('/') ?>">
            <input type="hidden" name="route" value="client.dashboard">
            <input name="q" placeholder="Raktažodis" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <input name="city" placeholder="Miestas" value="<?= htmlspecialchars($_GET['city'] ?? '') ?>">
            <input name="speciality" placeholder="Specializacija" value="<?= htmlspecialchars($_GET['speciality'] ?? '') ?>">
            <button>Ieškoti</button>
        </form>
    </div>
</div>

<div class="card">
    <h3>Mano projektai</h3>
    <?php foreach ($projects as $project): ?>
        <div style="border-top:1px solid #e5e7eb;padding-top:8px;margin-top:8px;">
            <strong><?= htmlspecialchars($project['title']) ?></strong> · <?= htmlspecialchars($project['city']) ?> · €<?= htmlspecialchars((string)$project['budget']) ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h3>Rangovų sąrašas ir užklausų siuntimas</h3>
    <?php foreach ($contractors as $contractor): ?>
        <div style="border-top:1px solid #e5e7eb;padding-top:10px;margin-top:10px;">
            <strong><?= htmlspecialchars($contractor['name']) ?></strong>
            <p><?= htmlspecialchars($contractor['city']) ?> · <?= htmlspecialchars($contractor['speciality']) ?></p>
            <p><?= htmlspecialchars($contractor['profile_text'] ?? '') ?></p>
            <?php if (!empty($projects)): ?>
                <form method="post" action="<?= \App\Core\Url::route('client.inquiry.send') ?>">
                    <input type="hidden" name="contractor_id" value="<?= (int)$contractor['id'] ?>">
                    <select name="project_id">
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= (int)$project['id'] ?>"><?= htmlspecialchars($project['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <textarea name="message" placeholder="Užklausos tekstas" required></textarea>
                    <button>Siųsti užklausą + automatizuotas el. laiškas</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
