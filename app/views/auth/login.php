<h2>Prisijungimas</h2>
<form method="post"><?= csrf_field() ?>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Slaptažodis" required>
<button class="btn">Prisijungti</button>
</form>
