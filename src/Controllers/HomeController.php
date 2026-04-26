<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Core\Url;
use App\Core\View;
use App\Repositories\AdRepository;

final class HomeController
{
    public function index(): void
    {
        $repo = new AdRepository();
        $ads = $repo->activeAdsForShowcase();
        $repo->registerImpressions($ads);

        View::render('home/index', ['ads' => $ads]);
    }

    public function adClick(): void
    {
        $adId = (int)($_GET['id'] ?? 0);
        if ($adId > 0) {
            (new AdRepository())->trackClick($adId);
            Session::flash('Ačiū! Reklama atidaryta, paspaudimas užregistruotas.');
        }

        header('Location: ' . Url::to('/'));
        exit;
    }
}
