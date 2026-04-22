<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'projects' => Project::count(),
                'blogs' => Blog::count(),
                'testimonials' => Testimonial::count(),
                'contacts' => Contact::count(),
            ],
            'recentProjects' => Project::latest('created_at')
                ->limit(5)
                ->get([
                    'id',
                    'title',
                    'summary as excerpt',
                    'status',
                    'created_at as date'
                ])
                ->map(fn ($project) => (array) $project)
                ->toArray(),
            'recentBlogs' => Blog::latest('created_at')
                ->limit(5)
                ->get([
                    'id',
                    'title',
                    'excerpt',
                    'status',
                    'created_at as date'
                ])
                ->map(fn ($blog) => (array) $blog)
                ->toArray(),
            'recentContacts' => Contact::latest('created_at')
                ->limit(5)
                ->get([
                    'id',
                    'subject as title',
                    'message as excerpt',
                    'status',
                    'created_at as date'
                ])
                ->map(fn ($contact) => (array) $contact)
                ->toArray(),
        ]);
    }
}

