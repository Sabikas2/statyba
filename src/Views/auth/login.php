<div class="card">
    <h2>Prisijungimas</h2>
    <p>Demo admin: admin@statyba.lt / Admin123!</p>
    <form method="post" action="<?= \App\Core\Url::route('login.submit') ?>">
    <form method="post" action="/?route=login.submit">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Slaptažodis</label>
        <input type="password" name="password" required>
        <button>Prisijungti</button>
    </form>
</div>
