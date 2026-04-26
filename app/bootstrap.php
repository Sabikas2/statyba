<?php
declare(strict_types=1);

session_start();

date_default_timezone_set('Europe/Vilnius');

$configFile = __DIR__ . '/../config.php';
$sampleFile = __DIR__ . '/../config.sample.php';

if (!file_exists($configFile)) {
    if (basename($_SERVER['SCRIPT_NAME'] ?? '') !== 'install.php') {
        header('Location: install.php');
        exit;
    }
}

$GLOBALS['config'] = file_exists($configFile) ? require $configFile : require $sampleFile;

require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require __DIR__ . '/csrf.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/mailer.php';
require __DIR__ . '/ai.php';

spl_autoload_register(function (string $class): void {
    if (!str_starts_with($class, 'App\\Controllers\\')) {
        return;
    }
    $name = substr($class, strlen('App\\Controllers\\'));
    $file = __DIR__ . '/controllers/' . $name . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

if (!is_dir(__DIR__ . '/../storage/logs')) {
    @mkdir(__DIR__ . '/../storage/logs', 0775, true);
}
if (!is_dir(__DIR__ . '/../storage/uploads')) {
    @mkdir(__DIR__ . '/../storage/uploads', 0775, true);
}
