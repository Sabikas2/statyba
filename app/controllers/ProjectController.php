<?php
declare(strict_types=1);
namespace App\Controllers;

final class ProjectController {
    public static function new(): void {
        require_role('client');
        $cats = db()->query('SELECT * FROM project_categories ORDER BY name')->fetchAll();
        view('client/project_new',['categories'=>$cats]);
    }

    public static function create(): void {
        require_role('client');
        $st = db()->prepare('INSERT INTO projects (client_id,title,category_id,city,region,address_optional,budget_min,budget_max,desired_start_date,description,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $st->execute([(int)current_user()['id'],trim($_POST['title']), (int)$_POST['category_id'], trim($_POST['city']), trim($_POST['region']), trim($_POST['address_optional']??''), (float)$_POST['budget_min'], (float)$_POST['budget_max'], $_POST['desired_start_date']?:null, trim($_POST['description']), 'open', now(), now()]);
        $pid = (int)db()->lastInsertId();
        if (!empty($_FILES['project_file']['name'])) {
            $up = upload_file($_FILES['project_file']);
            if ($up) {
                $f = db()->prepare('INSERT INTO project_files (project_id,original_name,file_path,mime_type,size,created_at) VALUES (?,?,?,?,?,?)');
                $f->execute([$pid,$up['original_name'],$up['file_path'],$up['mime_type'],$up['size'],now()]);
            }
        }
        // notify admin
        $a = db()->query("SELECT email FROM users WHERE role='admin' AND status='active' LIMIT 1")->fetch();
        if ($a) { $ok=smtp_send($a['email'],'Naujas projektas','Naujas projektas #' . $pid); log_email($a['email'],'Naujas projektas',$ok?'sent':'failed',null,$pid); }
        redirect('project.view&id=' . $pid);
    }

    public static function view(): void {
        require_login();
        $id = (int)($_GET['id'] ?? 0);
        $st = db()->prepare('SELECT p.*, c.name category_name FROM projects p LEFT JOIN project_categories c ON c.id=p.category_id WHERE p.id=?');
        $st->execute([$id]);
        $project = $st->fetch();
        if (!$project) { echo 'Projektas nerastas'; return; }
        if (current_user()['role']==='client' && (int)$project['client_id'] !== (int)current_user()['id']) { echo 'Draudžiama'; return; }

        $inv = db()->prepare('SELECT i.*, cp.company_name, cp.email, cp.city FROM project_invites i JOIN contractor_profiles cp ON cp.id=i.contractor_profile_id WHERE i.project_id=? ORDER BY i.id DESC');
        $inv->execute([$id]);
        $b = db()->prepare('SELECT b.*, cp.company_name FROM bids b JOIN contractor_profiles cp ON cp.id=b.contractor_profile_id WHERE b.project_id=? ORDER BY b.id DESC');
        $b->execute([$id]);
        $bids = $b->fetchAll();
        $stats = bids_stats($bids);
        $analysis = ai_analyze_bids($project,$bids);
        view('client/project_view',['project'=>$project,'invites'=>$inv->fetchAll(),'bids'=>$bids,'stats'=>$stats,'analysis'=>$analysis]);
    }

    public static function selectContractor(): void {
        require_role('client');
        $bidId = (int)($_POST['bid_id'] ?? 0);
        $st = db()->prepare('UPDATE bids SET status="selected",updated_at=? WHERE id=?');
        $st->execute([now(),$bidId]);
        flash('success','Rangovas pasirinktas.');
        redirect('project.view&id=' . (int)$_POST['project_id']);
    }
}
