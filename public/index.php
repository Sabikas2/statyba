<?php

declare(strict_types=1);

use App\Core\Router;
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

$route = $_GET['route'] ?? '';
Router::dispatch($route);
