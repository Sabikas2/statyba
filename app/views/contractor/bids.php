<h2>Kvietimas: <?= e($invite['title']) ?></h2>
<p><?= e($invite['description']) ?></p>
<form method="post" action="<?= url('bid.submit') ?>"><?= csrf_field() ?>
<input type="hidden" name="project_id" value="<?= (int)$invite['project_id'] ?>">
<input type="hidden" name="contractor_profile_id" value="<?= (int)$invite['contractor_profile_id'] ?>">
<input name="price" type="number" step="0.01" placeholder="Kaina" required>
<select name="price_type"><option value="fixed">Fixed</option><option value="estimate">Estimate</option><option value="hourly">Hourly</option></select>
<input name="duration_days" type="number" placeholder="Trukmė dienomis" required>
<label><input type="checkbox" name="includes_materials"> Medžiagos įskaičiuotos</label>
<input name="warranty_months" type="number" placeholder="Garantija mėn.">
<textarea name="message" placeholder="Komentaras"></textarea>
<button class="btn">Pateikti pasiūlymą</button>
</form>
