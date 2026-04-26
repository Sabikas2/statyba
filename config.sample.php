<?php
return [
    'app_name' => 'BuildMatch AI',
    'base_url' => '',
    'env' => 'production',
    'db' => [
        'host' => 'localhost',
        'port' => '3306',
        'name' => 'buildmatch',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'smtp' => [
        'host' => '',
        'port' => 587,
        'username' => '',
        'password' => '',
        'from_email' => 'no-reply@example.com',
        'from_name' => 'BuildMatch AI',
    ],
    'openai_key' => '',
    'settings' => [
        'max_invites_per_project' => 20,
        'platform_fee_percent' => 5,
    ],
];
