<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\ContractorController;
use App\Controllers\HomeController;

final class Router
{
    public static function dispatch(string $route): void
    {
        $home = new HomeController();
        $auth = new AuthController();
        $client = new ClientController();
        $contractor = new ContractorController();
        $admin = new AdminController();

        match ($route) {
            'register' => $auth->register(),
            'register.submit' => $auth->registerSubmit(),
            'login' => $auth->login(),
            'login.submit' => $auth->loginSubmit(),
            'logout' => $auth->logout(),

            'client.dashboard' => $client->dashboard(),
            'client.project.create' => $client->createProject(),
            'client.inquiry.send' => $client->sendInquiry(),

            'contractor.dashboard' => $contractor->dashboard(),
            'contractor.profile.update' => $contractor->updateProfile(),
            'contractor.ad.create' => $contractor->createAd(),

            'admin.dashboard' => $admin->dashboard(),
            'admin.contractor.approve' => $admin->approveContractor(),
            'admin.ad.approve' => $admin->approveAd(),

            default => $home->index(),
        };
    }
}
