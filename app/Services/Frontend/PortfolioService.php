<?php

namespace App\Services\Frontend;

use App\Models\About;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class PortfolioService
{
    /**
     * Get data for home page - eager loaded, cached.
     */
    public function getHomeData(): array
    {
        return Cache::remember('portfolio_home_data', 3600, function () {
            return [
                'banners' => Banner::active()->published()->orderBy('sort_order')->limit(5)->get(),
                'featured_projects' => Project::featured()->published()->with('skills')->latest()->take(6)->get(),
                'skills' => Skill::published()->orderBy('name')->limit(12)->get(),
                'testimonials' => Testimonial::published()->latest()->limit(8)->get(),
                'recent_blogs' => Blog::published()->latest()->take(3)->get(),
                'about' => About::published()->first(),
            ];
        });
    }
}
