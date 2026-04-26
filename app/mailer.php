<?php
declare(strict_types=1);

function smtp_send(string $to, string $subject, string $body): bool {
    $host = (string)config('smtp.host');
    if ($host === '') {
        return @mail($to, $subject, $body, 'From: ' . config('smtp.from_email'));
    }
    $port = (int)config('smtp.port', 587);
    $fp = @fsockopen($host, $port, $errno, $errstr, 10);
    if (!$fp) { log_error("SMTP connect failed: $errno $errstr"); return false; }
    fclose($fp);
    return @mail($to, $subject, $body, 'From: ' . config('smtp.from_email'));
}

function log_email(string $email, string $subject, string $status, ?string $error = null, ?int $projectId = null): void {
    $stmt = db()->prepare('INSERT INTO email_logs (recipient_email,subject,body_preview,status,error_message,related_project_id,created_at) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([$email,$subject,substr($subject,0,120),$status,$error,$projectId,now()]);
}

function send_project_invite(array $contractor, array $project, string $token): void {
    if ((int)$contractor['consent_to_contact'] !== 1 || $contractor['status'] !== 'approved') return;
    $link = (config('base_url','') ?: '') . '/index.php?r=invite.view&token=' . urlencode($token);
    $unsub = (config('base_url','') ?: '') . '/index.php?r=contractor.optout&id=' . (int)$contractor['id'];
    $subject = 'Naujas projektas: ' . $project['title'];
    $body = "Naujas projektas: {$project['title']}\nPeržiūra: {$link}\nAtsisakyti: {$unsub}";
    $ok = smtp_send($contractor['email'], $subject, $body);
    log_email($contractor['email'], $subject, $ok ? 'sent' : 'failed', $ok ? null : 'SMTP fail', (int)$project['id']);
}
