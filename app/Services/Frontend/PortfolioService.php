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
     *
     * @return array<string, mixed>
     */
    public function getHomeData(): array
    {
        Cache::forget('portfolio_home_data');

        return Cache::remember('portfolio_home_data', 3600, function () {
            return [
                'banners' => Banner::published()
                    ->orderBy('sort_order')
                    ->limit(5)
                    ->get([
                        'id',
                        'title',
                        'subtitle',
                        'image_path',
                        'cta_text',
                        'cta_url',
                    ])
                    ->values()
                    ->all(),
                'featured_projects' => Project::featured()
                    ->published()
                    ->latest()
                    ->take(6)
                    ->get([
                        'id',
                        'title',
                        'summary',
                        'thumbnail_path',
                        'project_url',
                        'repository_url',
                    ])
                    ->values()
                    ->all(),
                'skills' => Skill::published()
                    ->orderBy('name')
                    ->limit(12)
                    ->get([
                        'id',
                        'name',
                        'proficiency',
                        'icon',
                    ])
                    ->values()
                    ->all(),
                'testimonials' => Testimonial::published()
                    ->latest()
                    ->limit(8)
                    ->get([
                        'id',
                        'client_name',
                        'client_role',
                        'company',
                        'quote',
                        'rating',
                    ])
                    ->values()
                    ->all(),
                'recent_blogs' => Blog::published()
                    ->latest()
                    ->take(3)
                    ->get([
                        'id',
                        'title',
                        'slug',
                        'excerpt',
                        'featured_image',
                        'published_at',
                    ])
                    ->values()
                    ->all(),
                'about' => About::published()
                    ->first([
                        'heading',
                        'content',
                        'photo_path',
                        'resume_url',
                    ])
                    ?->toArray(),
            ];
        });
    }
}
