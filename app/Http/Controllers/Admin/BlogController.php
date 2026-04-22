<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Services\Admin\BlogService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogService $blogService): Response
    {
        return Inertia::render('Admin/Blogs/Index', [
            'blogs' => $blogService->list(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(BlogRequest $request, BlogService $blogService): RedirectResponse
    {
        $blogService->create($request->validated());

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(BlogRequest $request, Blog $blog, BlogService $blogService): RedirectResponse
    {
        $blogService->update($blog, $request->validated());

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, BlogService $blogService): RedirectResponse
    {
        $blogService->delete($blog);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
