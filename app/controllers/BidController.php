<?php
declare(strict_types=1);
namespace App\Controllers;

final class BidController {
    public static function submit(): void {
        $projectId=(int)($_POST['project_id']??0);
        $contractorProfileId=(int)($_POST['contractor_profile_id']??0);
        if ($contractorProfileId===0 && current_user() && current_user()['role']==='contractor') {
            $st=db()->prepare('SELECT id,email FROM contractor_profiles WHERE user_id=? LIMIT 1');
            $st->execute([(int)current_user()['id']]);
            $p=$st->fetch(); $contractorProfileId=(int)$p['id'];
        }
        $st=db()->prepare('INSERT INTO bids (project_id,contractor_profile_id,price,price_type,duration_days,message,includes_materials,warranty_months,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
        $st->execute([$projectId,$contractorProfileId,(float)$_POST['price'],$_POST['price_type']??'estimate',(int)$_POST['duration_days'],trim($_POST['message']??''),isset($_POST['includes_materials'])?1:0,(int)$_POST['warranty_months'],'submitted',now(),now()]);

        $c = db()->prepare('SELECT u.email FROM projects p JOIN users u ON u.id=p.client_id WHERE p.id=?');
        $c->execute([$projectId]); $client=$c->fetch();
        if ($client) { $ok=smtp_send($client['email'],'Gautas naujas pasiūlymas','Projektui #' . $projectId . ' gautas naujas pasiūlymas.'); log_email($client['email'],'Gautas naujas pasiūlymas',$ok?'sent':'failed',null,$projectId); }
        flash('success','Pasiūlymas pateiktas.');
        redirect('project.view&id=' . $projectId);
    }
}
