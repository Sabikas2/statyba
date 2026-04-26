<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ProjectRepository
{
    public function create(int $clientId, string $title, string $description, string $city, float $budget): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO projects (client_id, title, description, city, budget, status) VALUES (:client_id,:title,:description,:city,:budget,:status)');
        $stmt->execute([
            ':client_id' => $clientId,
            ':title' => $title,
            ':description' => $description,
            ':city' => $city,
            ':budget' => $budget,
            ':status' => 'active',
        ]);
    }

    public function byClient(int $clientId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM projects WHERE client_id = :client_id ORDER BY id DESC');
        $stmt->execute([':client_id' => $clientId]);

        return $stmt->fetchAll();
    }
}
