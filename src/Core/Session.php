<?php

declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function flash(string $message): void
    {
        $_SESSION['flash'] = $message;
    }

    public static function pullFlash(): ?string
    {
        $message = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return is_string($message) ? $message : null;
    }
}
