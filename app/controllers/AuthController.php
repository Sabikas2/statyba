<?php
declare(strict_types=1);
namespace App\Controllers;

final class AuthController {
    public static function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = db()->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
            $stmt->execute([trim($_POST['email'] ?? '')]);
            $u = $stmt->fetch();
            if ($u && password_verify($_POST['password'] ?? '', $u['password_hash']) && $u['status'] === 'active') {
                login_user($u); redirect($u['role'] . '.dashboard');
            }
            flash('error','Neteisingi duomenys arba vartotojas neaktyvus.');
        }
        view('auth/login');
    }

    public static function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = ($_POST['role'] ?? 'client') === 'contractor' ? 'contractor' : 'client';
            $email = trim($_POST['email'] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { flash('error','Blogas email'); view('auth/register'); return; }
            $stmt = db()->prepare('INSERT INTO users (name,email,phone,password_hash,role,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?)');
            $stmt->execute([trim($_POST['name']??''),$email,trim($_POST['phone']??''),password_hash($_POST['password']??'',PASSWORD_DEFAULT),$role,'active',now(),now()]);
            $id = (int)db()->lastInsertId();
            if ($role === 'contractor') {
                $p = db()->prepare('INSERT INTO contractor_profiles (user_id,company_name,contact_person,email,phone,city,region,description,categories,service_radius_km,consent_to_contact,source,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
                $p->execute([$id,trim($_POST['company_name']??''),trim($_POST['name']??''),$email,trim($_POST['phone']??''),trim($_POST['city']??''),trim($_POST['region']??''),'',json_encode([]),30,1,'self_registered','pending',now(),now()]);
            }
            flash('success','Registracija sėkminga. Prisijunkite.'); redirect('login');
        }
        view('auth/register');
    }

    public static function logout(): void { logout_user(); redirect('home'); }
}
