<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function attempt(array $user): void
    {
        $_SESSION['user'] = $user;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    public static function requireRole(array|string $roles): void
    {
        $roles = (array)$roles;
        if (!self::check() || !in_array($_SESSION['user']['role'], $roles, true)) {
            header('Location: /?route=login');
            exit;
        }
    }
}
