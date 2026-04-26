<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class InquiryRepository
{
    public function create(int $projectId, int $clientId, int $contractorId, string $message): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO inquiries (project_id, client_id, contractor_id, message) VALUES (:project_id,:client_id,:contractor_id,:message)');
        $stmt->execute([
            ':project_id' => $projectId,
            ':client_id' => $clientId,
            ':contractor_id' => $contractorId,
            ':message' => $message,
        ]);
    }

    public function byContractor(int $contractorId): array
    {
        $stmt = Database::connection()->prepare('SELECT i.*, p.title AS project_title, u.name AS client_name FROM inquiries i JOIN projects p ON p.id = i.project_id JOIN users u ON u.id = i.client_id WHERE contractor_id = :contractor_id ORDER BY i.id DESC');
        $stmt->execute([':contractor_id' => $contractorId]);
        return $stmt->fetchAll();
    }
}
