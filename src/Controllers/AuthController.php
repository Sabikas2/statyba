<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Core\Url;
use App\Core\Session;
use App\Repositories\UserRepository;

final class AuthController
{
    public function register(): void
    {
        View::render('auth/register');
    }

    public function registerSubmit(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            Session::flash('Užpildykite privalomus laukus.');
            header('Location: ' . Url::route('register'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('Neteisingas el. pašto formatas.');
            header('Location: ' . Url::route('register'));
            exit;
        }

        $repo = new UserRepository();
        $repo->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $_POST['role'] ?? 'client',
            'city' => trim($_POST['city'] ?? ''),
            'speciality' => trim($_POST['speciality'] ?? ''),
        ]);

        header('Location: ' . Url::route('login'));
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
            Session::flash('Neteisingas el. paštas arba slaptažodis.');
            header('Location: ' . Url::route('login'));
            exit;
        }

        if ($user['role'] === 'contractor' && (int)$user['approved'] !== 1) {
            Session::flash('Jūsų rangovo profilis dar nepatvirtintas admin.');
            header('Location: ' . Url::route('login'));
            exit;
        }

        Auth::attempt($user);
        header('Location: ' . Url::route($user['role'] . '.dashboard'));
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . Url::to('/'));
        exit;
    }
}
