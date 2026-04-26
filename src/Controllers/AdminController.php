<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Core\Url;
use App\Repositories\AdRepository;
use App\Repositories\UserRepository;

final class AdminController
{
    public function dashboard(): void
    {
        Auth::requireRole('admin');

        View::render('admin/dashboard', [
            'pendingContractors' => (new UserRepository())->pendingContractors(),
            'pendingAds' => (new AdRepository())->pending(),
        ]);
    }

    public function approveContractor(): void
    {
        Auth::requireRole('admin');
        (new UserRepository())->approveContractor((int)($_POST['user_id'] ?? 0));
        header('Location: ' . Url::route('admin.dashboard'));
        exit;
    }

    public function approveAd(): void
    {
        Auth::requireRole('admin');
        (new AdRepository())->approve((int)($_POST['ad_id'] ?? 0));
        header('Location: ' . Url::route('admin.dashboard'));
        exit;
    }
}
