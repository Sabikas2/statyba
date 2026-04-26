<?php
declare(strict_types=1);

function config(string $key, $default = null) {
    $value = $GLOBALS['config'] ?? [];
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

function e(?string $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function now(): string { return date('Y-m-d H:i:s'); }
function url(string $r = ''): string { return 'index.php' . ($r !== '' ? '?r=' . urlencode($r) : ''); }
function redirect(string $r): void { header('Location: ' . url($r)); exit; }
function flash(string $k, ?string $v = null): ?string {
    if ($v !== null) { $_SESSION['_flash'][$k] = $v; return null; }
    $val = $_SESSION['_flash'][$k] ?? null; unset($_SESSION['_flash'][$k]); return $val;
}
function view(string $path, array $data = []): void {
    extract($data, EXTR_SKIP);
    include __DIR__ . '/views/layout/header.php';
    include __DIR__ . '/views/' . $path . '.php';
    include __DIR__ . '/views/layout/footer.php';
}
function log_error(string $msg): void {
    file_put_contents(__DIR__ . '/../storage/logs/app.log', '[' . date('c') . '] ' . $msg . "\n", FILE_APPEND);
}

function upload_file(array $file): ?array {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
    $allowed = ['application/pdf','image/jpeg','image/png','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (($file['size'] ?? 0) > 10 * 1024 * 1024) return null;
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowed, true)) return null;
    $name = bin2hex(random_bytes(10));
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $path = 'storage/uploads/' . $name . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../' . $path)) return null;
    return ['original_name' => $file['name'], 'file_path' => $path, 'mime_type' => $mime, 'size' => (int)$file['size']];
}

function require_login(): void { if (empty($_SESSION['user'])) redirect('login'); }
function require_role(string $role): void { require_login(); if (($_SESSION['user']['role'] ?? '') !== $role) redirect('home'); }
