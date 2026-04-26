<h2>Registracija</h2>
<form method="post"><?= csrf_field() ?>
<input name="name" placeholder="Vardas" required>
<input name="email" type="email" placeholder="Email" required>
<input name="phone" placeholder="Telefonas">
<input name="password" type="password" placeholder="Slaptažodis" required>
<select name="role"><option value="client">Klientas</option><option value="contractor">Rangovas</option></select>
<input name="company_name" placeholder="Įmonė (rangovui)">
<input name="city" placeholder="Miestas"><input name="region" placeholder="Regionas">
<button class="btn">Registruotis</button>
</form>
