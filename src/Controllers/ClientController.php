<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Core\Url;
use App\Core\Session;
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
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $budget = (float)($_POST['budget'] ?? 0);

        if ($title === '' || $description === '' || $city === '' || $budget <= 0) {
            Session::flash('Neteisingi projekto duomenys.');
            header('Location: ' . Url::route('client.dashboard'));
            exit;
        }

        (new ProjectRepository())->create(
            (int)Auth::user()['id'],
            $title,
            $description,
            $city,
            $budget
        );

        header('Location: ' . Url::route('client.dashboard'));
        exit;
    }

    public function sendInquiry(): void
    {
        Auth::requireRole('client');

        $message = trim($_POST['message'] ?? '');
        if ($message === '') {
            Session::flash('Užklausos tekstas negali būti tuščias.');
            header('Location: ' . Url::route('client.dashboard'));
            exit;
        }

        $inquiryRepo = new InquiryRepository();
        $inquiryRepo->create(
            (int)($_POST['project_id'] ?? 0),
            (int)Auth::user()['id'],
            (int)($_POST['contractor_id'] ?? 0),
            $message
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
                "Klientas " . Auth::user()['name'] . " atsiuntė užklausą: " . $message
            );
        }

        header('Location: ' . Url::route('client.dashboard'));
        exit;
    }
}
