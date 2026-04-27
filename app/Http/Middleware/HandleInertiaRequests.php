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
            'sidebarCounts' => [
                'unreadContacts' => Contact::where('is_read', false)->count(),
                'draftBlogs' => Blog::where('status', 'draft')->count(),
                'draftBanners' => Banner::where('status', 'draft')->count(),
                'draftProjects' => Project::where('status', 'draft')->count(),
                'inactiveSkills' => Skill::where('status', 'inactive')->count(),
                'inactiveTestimonials' => Testimonial::where('status', 'inactive')->count(),
            ],
        ];
    }
}
