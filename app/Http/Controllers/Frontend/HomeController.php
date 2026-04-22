<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\PortfolioService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(PortfolioService $portfolioService): Response
    {
        $homeData = $portfolioService->getHomeData();

        Log::info('Homepage payload loaded.', [
            'banners_count' => count($homeData['banners']),
            'has_about' => $homeData['about'] !== null,
            'skills_count' => count($homeData['skills']),
            'featured_projects_count' => count($homeData['featured_projects']),
            'testimonials_count' => count($homeData['testimonials']),
            'recent_blogs_count' => count($homeData['recent_blogs']),
        ]);

        return Inertia::render('Front/Home', $homeData);
    }
}
