<?php
declare(strict_types=1);
namespace App\Controllers;

final class ContractorController {
    public static function dashboard(): void {
        require_role('contractor');
        $uid = (int)current_user()['id'];
        $cp = db()->prepare('SELECT * FROM contractor_profiles WHERE user_id=? LIMIT 1');
        $cp->execute([$uid]);
        $profile = $cp->fetch();
        $inv = db()->prepare('SELECT i.*, p.title, p.city FROM project_invites i JOIN projects p ON p.id=i.project_id WHERE i.contractor_profile_id=? ORDER BY i.id DESC');
        $inv->execute([(int)$profile['id']]);
        $b = db()->prepare('SELECT * FROM bids WHERE contractor_profile_id=? ORDER BY id DESC');
        $b->execute([(int)$profile['id']]);
        view('contractor/dashboard',['profile'=>$profile,'invites'=>$inv->fetchAll(),'bids'=>$b->fetchAll()]);
    }

    public static function profile(): void {
        require_role('contractor');
        $uid=(int)current_user()['id'];
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $cats = json_encode(array_filter(array_map('trim', explode(',', (string)($_POST['categories'] ?? '')))));
            $st=db()->prepare('UPDATE contractor_profiles SET company_name=?,city=?,region=?,description=?,categories=?,service_radius_km=?,consent_to_contact=?,updated_at=? WHERE user_id=?');
            $st->execute([trim($_POST['company_name']),trim($_POST['city']),trim($_POST['region']),trim($_POST['description']),$cats,(int)$_POST['service_radius_km'],isset($_POST['consent_to_contact'])?1:0,now(),$uid]);
            flash('success','Profilis išsaugotas');
            redirect('contractor.profile');
        }
        $st=db()->prepare('SELECT * FROM contractor_profiles WHERE user_id=?');$st->execute([$uid]);
        view('contractor/profile',['profile'=>$st->fetch()]);
    }

    public static function inviteView(): void {
        $token = trim($_GET['token'] ?? '');
        $st = db()->prepare('SELECT i.*, p.title, p.description, cp.id contractor_profile_id FROM project_invites i JOIN projects p ON p.id=i.project_id JOIN contractor_profiles cp ON cp.id=i.contractor_profile_id WHERE i.invite_token=? LIMIT 1');
        $st->execute([$token]);
        $invite = $st->fetch();
        if (!$invite) { echo 'Neteisingas token'; return; }
        view('contractor/bids',['invite'=>$invite]);
    }

    public static function optOut(): void {
        $id=(int)($_GET['id']??0);
        db()->prepare('UPDATE contractor_profiles SET consent_to_contact=0,updated_at=? WHERE id=?')->execute([now(),$id]);
        echo 'Atsisakymas išsaugotas.';
    }
}
