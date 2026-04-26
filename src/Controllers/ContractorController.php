<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Core\Url;
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

        (new UserRepository())->updateContractorProfile(
            (int)Auth::user()['id'],
            trim($_POST['profile_text'] ?? ''),
            trim($_POST['city'] ?? ''),
            trim($_POST['speciality'] ?? '')
        );

        header('Location: ' . Url::route('contractor.dashboard'));
        exit;
    }

    public function createAd(): void
    {
        Auth::requireRole('contractor');

        (new AdRepository())->create(
            (int)Auth::user()['id'],
            trim($_POST['title'] ?? ''),
            trim($_POST['description'] ?? ''),
            (float)($_POST['daily_budget'] ?? 0)
        );

        header('Location: ' . Url::route('contractor.dashboard'));
        exit;
    }
}
