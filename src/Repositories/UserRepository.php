<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class UserRepository
{
    public function create(array $data): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO users (name, email, password_hash, role, city, speciality, approved) VALUES (:name,:email,:hash,:role,:city,:speciality,:approved)');
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'],
            ':city' => $data['city'] ?? '',
            ':speciality' => $data['speciality'] ?? '',
            ':approved' => $data['role'] === 'contractor' ? 0 : 1,
        ]);
    }

    public function byEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);

        return $stmt->fetch() ?: null;
    }

    public function allApprovedContractors(string $q = '', string $city = '', string $speciality = ''): array
    {
        $sql = 'SELECT id, name, email, city, speciality, profile_text FROM users WHERE role = "contractor" AND approved = 1';
        $params = [];

        if ($q !== '') {
            $sql .= ' AND (name LIKE :q OR profile_text LIKE :q)';
            $params[':q'] = '%' . $q . '%';
        }
        if ($city !== '') {
            $sql .= ' AND city = :city';
            $params[':city'] = $city;
        }
        if ($speciality !== '') {
            $sql .= ' AND speciality = :speciality';
            $params[':speciality'] = $speciality;
        }

        $sql .= ' ORDER BY id DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function updateContractorProfile(int $id, string $profileText, string $city, string $speciality): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET profile_text=:profile_text, city=:city, speciality=:speciality WHERE id=:id');
        $stmt->execute([
            ':profile_text' => $profileText,
            ':city' => $city,
            ':speciality' => $speciality,
            ':id' => $id,
        ]);
    }

    public function pendingContractors(): array
    {
        return Database::connection()->query('SELECT id, name, email, city, speciality FROM users WHERE role = "contractor" AND approved = 0 ORDER BY id DESC')->fetchAll();
    }

    public function approveContractor(int $id): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET approved = 1 WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
