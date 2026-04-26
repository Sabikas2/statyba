# AGENTS.md

## Project rules for Codex agents
1. Keep runtime dependency-free: no Composer, no framework, no Node build step.
2. Target Hostinger Business shared hosting (PHP 8.2 + MySQL/MariaDB).
3. Never commit `config.php` with secrets; keep `config.sample.php` only.
4. Use PDO prepared statements for all SQL with user input.
5. Keep uploads inside `storage/uploads` and logs inside `storage/logs`.
6. Always add CSRF hidden fields to POST forms.
7. Prefer modifying files under `/app`, `/assets`, and root entry files (`index.php`, `install.php`, `.htaccess`, `README.md`).
8. After major changes, run `php -l` checks on all PHP files.
