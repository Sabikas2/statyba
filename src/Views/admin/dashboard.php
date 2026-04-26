<h1>Administratoriaus panelė</h1>

<div class="card">
    <h3>Nepatvirtinti rangovai</h3>
    <?php foreach ($pendingContractors as $user): ?>
        <div style="border-top:1px solid #e5e7eb;padding-top:8px;margin-top:8px;">
            <?= htmlspecialchars($user['name']) ?> · <?= htmlspecialchars($user['email']) ?> · <?= htmlspecialchars($user['city']) ?>
            <form method="post" action="<?= \App\Core\Url::route('admin.contractor.approve') ?>">
                <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                <button>Patvirtinti rangovą</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h3>Nepatvirtintos reklamos</h3>
    <?php foreach ($pendingAds as $ad): ?>
        <div style="border-top:1px solid #e5e7eb;padding-top:8px;margin-top:8px;">
            <strong><?= htmlspecialchars($ad['title']) ?></strong> (<?= htmlspecialchars($ad['contractor_name']) ?>) · €<?= htmlspecialchars((string)$ad['daily_budget']) ?>/d.
            <p><?= htmlspecialchars($ad['description']) ?></p>
            <form method="post" action="<?= \App\Core\Url::route('admin.ad.approve') ?>">
                <input type="hidden" name="ad_id" value="<?= (int)$ad['id'] ?>">
                <button>Patvirtinti reklamą</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
