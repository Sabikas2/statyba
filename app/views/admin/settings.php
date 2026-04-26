<h2>Nustatymai</h2><form method="post"><?= csrf_field() ?>
<input name="smtp_host" placeholder="SMTP host" value="<?= e($settings['smtp_host'] ?? '') ?>">
<input name="smtp_port" placeholder="SMTP port" value="<?= e($settings['smtp_port'] ?? '587') ?>">
<input name="smtp_username" placeholder="SMTP username" value="<?= e($settings['smtp_username'] ?? '') ?>">
<input name="smtp_password" placeholder="SMTP password" value="<?= e($settings['smtp_password'] ?? '') ?>">
<input name="openai_key" placeholder="OpenAI key" value="<?= e($settings['openai_key'] ?? '') ?>">
<input name="platform_fee" placeholder="Platform fee %" value="<?= e($settings['platform_fee'] ?? '5') ?>">
<input name="max_invites" placeholder="Max invites per project" value="<?= e($settings['max_invites'] ?? '20') ?>">
<button class="btn">Saugoti</button></form>
