<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

final class MigrationService
{
    public function migrate(): void
    {
        $db = Database::connection();

        $db->exec('CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(190) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL,
            city VARCHAR(120) DEFAULT "",
            speciality VARCHAR(120) DEFAULT "",
            profile_text TEXT DEFAULT "",
            approved INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            client_id INTEGER NOT NULL,
            title VARCHAR(180) NOT NULL,
            description TEXT NOT NULL,
            city VARCHAR(120) NOT NULL,
            budget DECIMAL(10,2) NOT NULL,
            status VARCHAR(30) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS inquiries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id INTEGER NOT NULL,
            client_id INTEGER NOT NULL,
            contractor_id INTEGER NOT NULL,
            message TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS ads (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            contractor_id INTEGER NOT NULL,
            title VARCHAR(180) NOT NULL,
            description TEXT NOT NULL,
            daily_budget DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) NOT NULL,
            impressions INTEGER NOT NULL DEFAULT 0,
            clicks INTEGER NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');

        $admin = $db->query("SELECT COUNT(*) AS c FROM users WHERE role = 'admin'")->fetch();
        if ((int)$admin['c'] === 0) {
            $hash = password_hash('Admin123!', PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (name,email,password_hash,role,approved) VALUES ('Platform Admin','admin@statyba.lt',:hash,'admin',1)");
            $stmt->execute([':hash' => $hash]);
        }
    }
}
