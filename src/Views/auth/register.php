<div class="card">
    <h2>Registracija</h2>
    <form method="post" action="<?= \App\Core\Url::route('register.submit') ?>">
    <form method="post" action="/?route=register.submit">
        <label>Vardas</label>
        <input name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Slaptažodis</label>
        <input type="password" name="password" required>

        <label>Rolė</label>
        <select name="role">
            <option value="client">Klientas</option>
            <option value="contractor">Rangovas</option>
        </select>

        <label>Miestas</label>
        <input name="city">

        <label>Specializacija (rangovui)</label>
        <input name="speciality">

        <button>Kurti paskyrą</button>
    </form>
</div>
