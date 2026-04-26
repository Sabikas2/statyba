<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Router;
use App\Core\Session;
use App\Core\Url;
use App\Services\EmailService;
use App\Services\MigrationService;

session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = dirname(__DIR__) . '/src/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require_once $path;
    }
});

(new MigrationService())->migrate();
(new EmailService())->processQueue();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
        Session::flash('Sesija pasibaigė arba neteisingas saugumo raktas. Bandykite dar kartą.');
        $back = $_SERVER['HTTP_REFERER'] ?? Url::to('/');
        header('Location: ' . $back);
        exit;
    }
}

$route = $_GET['route'] ?? '';
Router::dispatch($route);
