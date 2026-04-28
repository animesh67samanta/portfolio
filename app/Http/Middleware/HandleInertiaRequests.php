<?php

namespace App\Http\Middleware;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
            ],
            'seo' => [
                'siteName' => config('seo.site_name', config('app.name')),
                'defaultTitle' => config('seo.default_title', config('app.name')),
                'defaultDescription' => config('seo.default_description'),
                'defaultKeywords' => config('seo.default_keywords'),
                'defaultImage' => config('seo.default_image', '/logo.png'),
                'locale' => config('seo.locale', 'en_US'),
                'url' => config('app.url'),
                'twitter' => [
                    'card' => config('seo.twitter.card', 'summary_large_image'),
                    'site' => config('seo.twitter.site'),
                    'creator' => config('seo.twitter.creator'),
                ],
                'facebook' => [
                    'appId' => config('seo.facebook.app_id'),
                ],
                'robots' => config('seo.robots', 'index, follow'),
            ],
            'sidebarCounts' => [
                'unreadContacts' => Contact::where('is_read', '=', false)->count(),
                'draftBlogs' => Blog::where('status', '=', 'draft')->count(),
                'draftBanners' => Banner::where('status', '=', 'draft')->count(),
                'draftProjects' => Project::where('status', '=', 'draft')->count(),
                'inactiveSkills' => Skill::where('status', '=', 'inactive')->count(),
                'inactiveTestimonials' => Testimonial::where('status', '=', 'inactive')->count(),
            ],
            // In your controller
            
            'socialLinks' => [
                'linkedin' => env('LINKEDIN_URL', '#'),
                'github' => env('GITHUB_URL', '#'),
                'facebook' => env('FACEBOOK_URL', '#'),
            ],
            
        ];
    }
}
