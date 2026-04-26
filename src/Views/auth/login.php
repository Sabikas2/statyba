<div class="card">
    <h2>Prisijungimas</h2>
    <p>Demo admin: admin@statyba.lt / Admin123!</p>
    <form method="post" action="<?= \App\Core\Url::route('login.submit') ?>">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::token() ?>">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Slaptažodis</label>
        <input type="password" name="password" required>
        <button>Prisijungti</button>
    </form>
</div>
