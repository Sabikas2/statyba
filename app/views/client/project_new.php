<h2>Naujas projektas</h2>
<form method="post" action="<?= url('project.create') ?>" enctype="multipart/form-data"><?= csrf_field() ?>
<input name="title" placeholder="Pavadinimas" required>
<select name="category_id" required><?php foreach($categories as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select>
<input name="city" placeholder="Miestas" required><input name="region" placeholder="Regionas" required>
<input name="address_optional" placeholder="Adresas (nebūtina)">
<input name="budget_min" type="number" step="0.01" placeholder="Biudžetas nuo" required>
<input name="budget_max" type="number" step="0.01" placeholder="Biudžetas iki" required>
<input name="desired_start_date" type="date">
<textarea name="description" placeholder="Aprašymas" required></textarea>
<input type="file" name="project_file">
<button class="btn">Sukurti projektą</button></form>
