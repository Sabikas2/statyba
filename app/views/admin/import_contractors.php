<h2>CSV importas</h2>
<p>Formatas: company_name,email,phone,city,region,categories,website,consent_to_contact</p>
<?php if($result): ?><div class="flash ok"><?= e($result) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data"><?= csrf_field() ?><input type="file" name="csv" accept=".csv" required><button class="btn">Importuoti</button></form>
