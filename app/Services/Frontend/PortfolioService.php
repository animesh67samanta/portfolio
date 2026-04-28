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
                    ->orderBy('sort_order', 'asc')
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
                'experiences' => About::published()->first()?->experiences ?? [],
                'educations' => About::published()->first()?->educations ?? [],
                'skills' => Skill::published()
                    ->orderBy('sort_order', 'asc')
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
                    ->limit(9)
                    ->get([
                        'id',
                        'client_name',
                        'client_role',
                        'company',
                        'quote',
                        'avatar_path',
                        'rating',
                    ])
                    ->values()
                    ->all(),
                'recent_blogs' => Blog::published()
                    ->latest()
                    ->get([
                        'id',
                        'title',
                        'slug',
                        'excerpt',
                        'content',
                        'excerpt',
                        'featured_image',
                        'cover_image',
                        'created_at',
                        'published_at',
                        'status',
                        'views_count',
                    ])
                    ->values()
                    ->all(),
                'about' => About::published()
                    ->first([
                        'heading',
                        'content',
                        'photo_path',
                        'resume_url',
                        'status',
                        'name',
                        'email',
                        'mobile',
                        'current_address',
                        'permanent_address',
                        
                    ])
                    ?->toArray(),
            ];
        });
    }
}
