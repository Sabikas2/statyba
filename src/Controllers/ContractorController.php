<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Core\Url;
use App\Core\Session;
use App\Repositories\AdRepository;
use App\Repositories\InquiryRepository;
use App\Repositories\UserRepository;

final class ContractorController
{
    public function dashboard(): void
    {
        Auth::requireRole('contractor');

        $inquiries = (new InquiryRepository())->byContractor((int)Auth::user()['id']);
        $ads = (new AdRepository())->byContractor((int)Auth::user()['id']);

        View::render('contractor/dashboard', [
            'inquiries' => $inquiries,
            'ads' => $ads,
        ]);
    }

    public function updateProfile(): void
    {
        Auth::requireRole('contractor');

        $profileText = trim($_POST['profile_text'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $speciality = trim($_POST['speciality'] ?? '');

        if ($profileText === '' || $city === '' || $speciality === '') {
            Session::flash('Profilio laukai negali būti tušti.');
            header('Location: ' . Url::route('contractor.dashboard'));
            exit;
        }

        (new UserRepository())->updateContractorProfile(
            (int)Auth::user()['id'],
            $profileText,
            $city,
            $speciality
        );

        header('Location: ' . Url::route('contractor.dashboard'));
        exit;
    }

    public function createAd(): void
    {
        Auth::requireRole('contractor');

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $dailyBudget = (float)($_POST['daily_budget'] ?? 0);

        if ($title === '' || $description === '' || $dailyBudget <= 0) {
            Session::flash('Neteisingi reklamos duomenys.');
            header('Location: ' . Url::route('contractor.dashboard'));
            exit;
        }

        (new AdRepository())->create(
            (int)Auth::user()['id'],
            $title,
            $description,
            $dailyBudget
        );

        header('Location: ' . Url::route('contractor.dashboard'));
        exit;
    }
}
