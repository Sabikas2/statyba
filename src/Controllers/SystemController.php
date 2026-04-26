<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Core\Response;
use App\Services\EmailService;

final class SystemController
{
    public function health(): void
    {
        Response::json([
            'status' => 'ok',
            'time' => gmdate('c'),
            'service' => 'statyba-pro',
        ]);
    }

    public function processQueue(): void
    {
        $providedKey = $_GET['key'] ?? '';
        $expectedKey = (string)Config::get('QUEUE_SECRET', 'dev-secret');

        if (!is_string($providedKey) || $providedKey === '' || !hash_equals($expectedKey, $providedKey)) {
            Response::json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        (new EmailService())->processQueue();

        Response::json([
            'status' => 'ok',
            'message' => 'Queue processed',
            'time' => gmdate('c'),
        ]);
    }
}
