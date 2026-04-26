<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\AdRepository;

final class HomeController
{
    public function index(): void
    {
        $ads = (new AdRepository())->activeAdsForShowcase();
        View::render('home/index', ['ads' => $ads]);
    }
}
