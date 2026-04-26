<?php
declare(strict_types=1);
namespace App\Controllers;

final class PaymentController {
    public static function index(): void {
        require_login();
        if (current_user()['role'] === 'admin' && $_SERVER['REQUEST_METHOD']==='POST') {
            db()->prepare('UPDATE payments SET status=?, provider_ref=?, created_at=created_at WHERE id=?')->execute([$_POST['status'],trim($_POST['provider_ref']??''),(int)$_POST['id']]);
            flash('success','Mokėjimo būsena atnaujinta.');
            redirect('payments');
        }
        $q = current_user()['role']==='admin' ? db()->query('SELECT * FROM payments ORDER BY id DESC') : (function(){ $s=db()->prepare('SELECT * FROM payments WHERE user_id=? ORDER BY id DESC'); $s->execute([(int)current_user()['id']]); return $s;})();
        view('admin/projects',['projects'=>[],'logs'=>[],'payments'=>$q->fetchAll()]);
    }
}
