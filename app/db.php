<?php
declare(strict_types=1);

function db(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', config('db.host'), config('db.port', '3306'), config('db.name'), config('db.charset','utf8mb4'));
    $pdo = new PDO($dsn, (string)config('db.user'), (string)config('db.pass'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
