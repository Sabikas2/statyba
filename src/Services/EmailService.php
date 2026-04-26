<?php

declare(strict_types=1);

namespace App\Services;

final class EmailService
{
    private string $queueFile;
    private string $logFile;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        $this->queueFile = $root . '/storage/email_queue.json';
        $this->logFile = $root . '/storage/emails.log';
    }

    public function enqueue(string $to, string $subject, string $body): void
    {
        $queue = $this->readQueue();
        $queue[] = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        file_put_contents($this->queueFile, json_encode($queue, JSON_PRETTY_PRINT));
    }

    public function processQueue(): void
    {
        $queue = $this->readQueue();
        if ($queue === []) {
            return;
        }

        $remaining = [];
        foreach ($queue as $mail) {
            $ok = @mail($mail['to'], $mail['subject'], $mail['body']);
            if (!$ok) {
                file_put_contents($this->logFile, sprintf("[%s] TO:%s | %s\n%s\n\n", date('c'), $mail['to'], $mail['subject'], $mail['body']), FILE_APPEND);
            }
        }

        file_put_contents($this->queueFile, json_encode($remaining, JSON_PRETTY_PRINT));
    }

    private function readQueue(): array
    {
        if (!is_file($this->queueFile)) {
            return [];
        }

        $data = json_decode((string)file_get_contents($this->queueFile), true);
        return is_array($data) ? $data : [];
    }
}
