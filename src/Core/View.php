<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';
        include dirname(__DIR__) . '/Views/partials/header.php';
        include $viewPath;
        include dirname(__DIR__) . '/Views/partials/footer.php';
    }
}
