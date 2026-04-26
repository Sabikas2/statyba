<?php

declare(strict_types=1);

namespace App\Core;

final class Url
{
    public static function basePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $dir = str_replace('\\', '/', dirname($scriptName));

        if ($dir === '/' || $dir === '.') {
            return '';
        }

        return rtrim($dir, '/');
    }

    public static function to(string $path = ''): string
    {
        $base = self::basePath();
        $path = '/' . ltrim($path, '/');

        if ($path === '/') {
            return $base === '' ? '/' : $base . '/';
        }

        return $base . $path;
    }

    public static function route(string $route): string
    {
        $base = self::to('/');
        $separator = str_contains($base, '?') ? '&' : '?';

        return rtrim($base, '/') . '/'.$separator.'route=' . urlencode($route);
    }
}
