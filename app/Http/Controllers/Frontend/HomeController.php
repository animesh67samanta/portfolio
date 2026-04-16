<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\PortfolioService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(PortfolioService $portfolioService): Response
    {
        return Inertia::render('Front/Home', $portfolioService->getHomeData());
    }
}
