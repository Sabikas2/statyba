<?php
declare(strict_types=1);
namespace App\Controllers;

final class AdminController {
    public static function dashboard(): void {
        require_role('admin');
        $stats=[
            'users'=>db()->query('SELECT COUNT(*) c FROM users')->fetch()['c'],
            'projects'=>db()->query('SELECT COUNT(*) c FROM projects')->fetch()['c'],
            'contractors'=>db()->query('SELECT COUNT(*) c FROM contractor_profiles')->fetch()['c'],
            'invites'=>db()->query('SELECT COUNT(*) c FROM project_invites')->fetch()['c'],
            'bids'=>db()->query('SELECT COUNT(*) c FROM bids')->fetch()['c'],
        ];
        view('admin/dashboard',['stats'=>$stats]);
    }

    public static function users(): void {
        require_role('admin');
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            db()->prepare('UPDATE users SET status=?,updated_at=? WHERE id=?')->execute([$_POST['status'],now(),(int)$_POST['id']]);
            flash('success','Vartotojas atnaujintas');
            redirect('admin.users');
        }
        $u=db()->query('SELECT * FROM users ORDER BY id DESC')->fetchAll();
        view('admin/users',['users'=>$u]);
    }

    public static function contractors(): void {
        require_role('admin');
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            db()->prepare('UPDATE contractor_profiles SET status=?,consent_to_contact=?,updated_at=? WHERE id=?')->execute([$_POST['status'],isset($_POST['consent_to_contact'])?1:0,now(),(int)$_POST['id']]);
            redirect('admin.contractors');
        }
        $r=db()->query('SELECT cp.*,u.name user_name FROM contractor_profiles cp LEFT JOIN users u ON u.id=cp.user_id ORDER BY cp.id DESC')->fetchAll();
        view('admin/contractors',['rows'=>$r]);
    }

    public static function projects(): void {
        require_role('admin');
        $p=db()->query('SELECT p.*,u.name client_name,c.name category_name FROM projects p LEFT JOIN users u ON u.id=p.client_id LEFT JOIN project_categories c ON c.id=p.category_id ORDER BY p.id DESC')->fetchAll();
        $logs = db()->query('SELECT * FROM email_logs ORDER BY id DESC LIMIT 100')->fetchAll();
        view('admin/projects',['projects'=>$p,'logs'=>$logs]);
    }

    public static function importContractors(): void {
        require_role('admin');
        $result=null;
        if ($_SERVER['REQUEST_METHOD']==='POST' && !empty($_FILES['csv']['tmp_name'])) {
            $h=fopen($_FILES['csv']['tmp_name'],'r');
            $header=fgetcsv($h);
            $ins=0; $skip=0;
            while(($row=fgetcsv($h))!==false){
                $d=array_combine($header,$row);
                if (!filter_var($d['email']??'', FILTER_VALIDATE_EMAIL)) { $skip++; continue; }
                $exists=db()->prepare('SELECT id FROM contractor_profiles WHERE email=? OR phone=? OR company_name=? LIMIT 1');
                $exists->execute([trim($d['email']),trim($d['phone']),trim($d['company_name'])]);
                if ($exists->fetch()) { $skip++; continue; }
                $consent=((string)($d['consent_to_contact']??'0')==='1')?1:0;
                $status=$consent? 'approved':'pending';
                $q=db()->prepare('INSERT INTO contractor_profiles (company_name,email,phone,city,region,categories,website,consent_to_contact,source,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
                $q->execute([trim($d['company_name']),trim($d['email']),trim($d['phone']),trim($d['city']),trim($d['region']),json_encode(array_filter(array_map('trim',explode('|',(string)($d['categories']??''))))),trim($d['website']),$consent,'admin_import',$status,now(),now()]);
                $ins++;
            }
            fclose($h);
            $result="Importuota: $ins, praleista: $skip";
        }
        view('admin/import_contractors',['result'=>$result]);
    }

    public static function settings(): void {
        require_role('admin');
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            foreach (['smtp_host','smtp_port','smtp_username','smtp_password','openai_key','platform_fee','max_invites'] as $k) {
                $s=db()->prepare('REPLACE INTO settings (`key`,`value`) VALUES (?,?)');
                $s->execute([$k,(string)($_POST[$k]??'')]);
            }
            flash('success','Nustatymai išsaugoti DB lentelėje settings.');
            redirect('admin.settings');
        }
        $settings=[]; foreach(db()->query('SELECT * FROM settings')->fetchAll() as $r){$settings[$r['key']]=$r['value'];}
        view('admin/settings',['settings'=>$settings]);
    }
}
