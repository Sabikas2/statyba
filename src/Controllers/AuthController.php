<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Repositories\UserRepository;

final class AuthController
{
    public function register(): void
    {
        View::render('auth/register');
    }

    public function registerSubmit(): void
    {
        $repo = new UserRepository();
        $repo->create([
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? 'client',
            'city' => trim($_POST['city'] ?? ''),
            'speciality' => trim($_POST['speciality'] ?? ''),
        ]);

        header('Location: /?route=login');
        exit;
    }

    public function login(): void
    {
        View::render('auth/login');
    }

    public function loginSubmit(): void
    {
        $repo = new UserRepository();
        $user = $repo->byEmail(trim($_POST['email'] ?? ''));

        if (!$user || !password_verify($_POST['password'] ?? '', $user['password_hash'])) {
            $_SESSION['flash'] = 'Neteisingas el. paštas arba slaptažodis.';
            header('Location: /?route=login');
            exit;
        }

        if ($user['role'] === 'contractor' && (int)$user['approved'] !== 1) {
            $_SESSION['flash'] = 'Jūsų rangovo profilis dar nepatvirtintas admin.';
            header('Location: /?route=login');
            exit;
        }

        Auth::attempt($user);
        header('Location: /?route=' . $user['role'] . '.dashboard');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: /');
        exit;
    }
}
