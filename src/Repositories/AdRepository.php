<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class AdRepository
{
    public function create(int $contractorId, string $title, string $description, float $dailyBudget): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO ads (contractor_id, title, description, daily_budget, status, impressions, clicks) VALUES (:contractor_id,:title,:description,:daily_budget,:status,0,0)');
        $stmt->execute([
            ':contractor_id' => $contractorId,
            ':title' => $title,
            ':description' => $description,
            ':daily_budget' => $dailyBudget,
            ':status' => 'pending',
        ]);
    }

    public function byContractor(int $contractorId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM ads WHERE contractor_id = :contractor_id ORDER BY id DESC');
        $stmt->execute([':contractor_id' => $contractorId]);
        return $stmt->fetchAll();
    }

    public function pending(): array
    {
        return Database::connection()->query('SELECT a.*, u.name as contractor_name FROM ads a JOIN users u ON u.id = a.contractor_id WHERE status = "pending" ORDER BY a.id DESC')->fetchAll();
    }

    public function approve(int $id): void
    {
        $stmt = Database::connection()->prepare('UPDATE ads SET status = "active" WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public function activeAdsForShowcase(int $limit = 5): array
    {
        $stmt = Database::connection()->prepare('SELECT a.*, u.name as contractor_name, u.city, u.speciality FROM ads a JOIN users u ON u.id = a.contractor_id WHERE a.status = "active" ORDER BY a.id DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
