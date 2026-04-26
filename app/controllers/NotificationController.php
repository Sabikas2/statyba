<?php
declare(strict_types=1);
namespace App\Controllers;

final class NotificationController {
    public static function sendInvites(): void {
        require_role('admin');
        $projectId=(int)($_POST['project_id']??0);
        $project = db()->prepare('SELECT * FROM projects WHERE id=?'); $project->execute([$projectId]); $p=$project->fetch();
        if (!$p) { flash('error','Projektas nerastas'); redirect('admin.projects'); }

        $max = (int)config('settings.max_invites_per_project',20);
        $matched = self::matchContractors($p);
        $matched = array_slice($matched,0,$max);

        $ins = db()->prepare('INSERT INTO project_invites (project_id, contractor_profile_id, status, invite_token, sent_at, created_at) VALUES (?,?,?,?,?,?)');
        foreach ($matched as $c) {
            $token = bin2hex(random_bytes(16));
            $ins->execute([$projectId,(int)$c['id'],'sent',$token,now(),now()]);
            send_project_invite($c,$p,$token);
        }
        flash('success','Kvietimai išsiųsti: ' . count($matched));
        redirect('project.view&id=' . $projectId);
    }

    public static function matchContractors(array $project): array {
        $sql='SELECT cp.*, u.status user_status FROM contractor_profiles cp LEFT JOIN users u ON u.id=cp.user_id WHERE cp.status="approved" AND cp.consent_to_contact=1 AND (u.status IS NULL OR u.status!="blocked")';
        $st=db()->query($sql); $rows=$st->fetchAll();
        $filtered=[];
        foreach($rows as $r){
            $cats=json_decode((string)$r['categories'],true)?:[];
            $catMatch=in_array((string)$project['category_id'],$cats,true) || in_array((string)$project['category_name']??'', $cats, true);
            $locMatch=strtolower((string)$r['city'])===strtolower((string)$project['city']) || strtolower((string)$r['region'])===strtolower((string)$project['region']);
            if($catMatch && $locMatch) $filtered[]=$r;
        }
        usort($filtered, function(array $a, array $b): int {
            $ratingCmp = ((float)$b['rating_avg']) <=> ((float)$a['rating_avg']);
            if ($ratingCmp !== 0) return $ratingCmp;
            return strcmp((string)$a['city'], (string)$b['city']);
        });
        return $filtered;
    }
}
