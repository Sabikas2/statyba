<div class="card">
    <h1>Statybų platforma: klientai, rangovai ir administravimas</h1>
    <p>Veikianti platforma su rolėmis, užklausomis, paieška ir reklamos monetizacija.</p>
</div>

<div class="card">
    <h2>Aktyvios reklamos</h2>
    <div class="grid">
        <?php foreach ($ads as $ad): ?>
            <div class="card">
                <h3><?= htmlspecialchars($ad['title']) ?></h3>
                <p><?= htmlspecialchars($ad['description']) ?></p>
                <small><?= htmlspecialchars($ad['contractor_name']) ?> · <?= htmlspecialchars($ad['city']) ?> · <?= htmlspecialchars($ad['speciality']) ?></small>
            </div>
        <?php endforeach; ?>
    </div>
</div>
