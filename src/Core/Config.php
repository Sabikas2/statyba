<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    public static function get(string $key, mixed $default = null): mixed
    {
        static $env = null;
        if ($env === null) {
            $env = [];
            $path = dirname(__DIR__, 2) . '/.env';
            if (is_file($path)) {
                $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                        continue;
                    }
                    [$k, $v] = array_map('trim', explode('=', $line, 2));
                    $env[$k] = trim($v, "\"'");
                }
            }
        }

        return $_ENV[$key] ?? $_SERVER[$key] ?? $env[$key] ?? $default;
    }
}
