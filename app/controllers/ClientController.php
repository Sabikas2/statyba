<?php
declare(strict_types=1);
namespace App\Controllers;

final class ClientController {
    public static function home(): void { view('home'); }

    public static function dashboard(): void {
        require_role('client');
        $st = db()->prepare('SELECT * FROM projects WHERE client_id=? ORDER BY id DESC');
        $st->execute([(int)current_user()['id']]);
        view('client/dashboard',['projects'=>$st->fetchAll()]);
    }
}
