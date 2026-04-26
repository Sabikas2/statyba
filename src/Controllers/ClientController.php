<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Core\Url;
use App\Repositories\InquiryRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;

final class ClientController
{
    public function dashboard(): void
    {
        Auth::requireRole('client');
        $projectRepo = new ProjectRepository();
        $userRepo = new UserRepository();

        $projects = $projectRepo->byClient((int)Auth::user()['id']);
        $contractors = $userRepo->allApprovedContractors(
            trim($_GET['q'] ?? ''),
            trim($_GET['city'] ?? ''),
            trim($_GET['speciality'] ?? '')
        );

        View::render('client/dashboard', [
            'projects' => $projects,
            'contractors' => $contractors,
        ]);
    }

    public function createProject(): void
    {
        Auth::requireRole('client');
        (new ProjectRepository())->create(
            (int)Auth::user()['id'],
            trim($_POST['title'] ?? ''),
            trim($_POST['description'] ?? ''),
            trim($_POST['city'] ?? ''),
            (float)($_POST['budget'] ?? 0)
        );

        header('Location: ' . Url::route('client.dashboard'));
        header('Location: /?route=client.dashboard');
        exit;
    }

    public function sendInquiry(): void
    {
        Auth::requireRole('client');

        $inquiryRepo = new InquiryRepository();
        $inquiryRepo->create(
            (int)($_POST['project_id'] ?? 0),
            (int)Auth::user()['id'],
            (int)($_POST['contractor_id'] ?? 0),
            trim($_POST['message'] ?? '')
        );

        $userRepo = new UserRepository();
        $contractor = null;
        foreach ($userRepo->allApprovedContractors() as $candidate) {
            if ((int)$candidate['id'] === (int)($_POST['contractor_id'] ?? 0)) {
                $contractor = $candidate;
                break;
            }
        }

        if ($contractor) {
            (new EmailService())->enqueue(
                $contractor['email'],
                'Nauja užklausa iš statyba platformos',
                "Klientas " . Auth::user()['name'] . " atsiuntė užklausą: " . trim($_POST['message'] ?? '')
            );
        }

        header('Location: ' . Url::route('client.dashboard'));
        header('Location: /?route=client.dashboard');
        exit;
    }
}
