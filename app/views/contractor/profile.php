<h2>Profilis</h2>
<form method="post"><?= csrf_field() ?>
<input name="company_name" value="<?= e($profile['company_name'] ?? '') ?>" placeholder="Įmonė">
<input name="city" value="<?= e($profile['city'] ?? '') ?>" placeholder="Miestas">
<input name="region" value="<?= e($profile['region'] ?? '') ?>" placeholder="Regionas">
<input name="service_radius_km" type="number" value="<?= e((string)($profile['service_radius_km'] ?? 30)) ?>" placeholder="Spindulys km">
<input name="categories" value="<?= e(implode(',', json_decode((string)($profile['categories'] ?? '[]'), true) ?: [])) ?>" placeholder="Kategorijos (kableliu)">
<textarea name="description" placeholder="Aprašymas"><?= e($profile['description'] ?? '') ?></textarea>
<label><input type="checkbox" name="consent_to_contact" <?= !empty($profile['consent_to_contact']) ? 'checked' : '' ?>> Sutinku gauti kvietimus</label>
<button class="btn">Išsaugoti</button>
</form>
