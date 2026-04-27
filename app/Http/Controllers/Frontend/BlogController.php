<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    /**
     * Increment blog views and return full blog details.
     */
    public function view(Blog $blog): JsonResponse
    {
        $blog->increment('views_count');
        $blog->refresh();

        return response()->json([
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'excerpt' => $blog->excerpt,
            'content' => $blog->content,
            'featured_image' => $blog->featured_image,
            'cover_image' => $blog->cover_image,
            'status' => $blog->status,
            'created_at' => $blog->created_at->toDateTimeString(),
            'published_at' => $blog->published_at?->toDateTimeString(),
            'views_count' => $blog->views_count,
        ]);
    }
}

