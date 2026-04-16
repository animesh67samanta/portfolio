<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Blogs/Index', [
            'blogs' => Blog::query()->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(BlogRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['featured_image'] = $this->storePublicImage($request->file('featured_image'), 'blogs');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->storePublicImage($request->file('cover_image'), 'blogs');
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(BlogRequest $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('featured_image')) {
            $this->deletePublicPath($blog->featured_image);
            $validated['featured_image'] = $this->storePublicImage($request->file('featured_image'), 'blogs');
        } else {
            unset($validated['featured_image']);
        }

        if ($request->hasFile('cover_image')) {
            $this->deletePublicPath($blog->cover_image);
            $validated['cover_image'] = $this->storePublicImage($request->file('cover_image'), 'blogs');
        } else {
            unset($validated['cover_image']);
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        $this->deletePublicPath($blog->featured_image);
        $this->deletePublicPath($blog->cover_image);
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }

    private function storePublicImage(UploadedFile $file, string $folder): string
    {
        $relativeDir = "uploads/{$folder}";
        $absoluteDir = public_path($relativeDir);

        File::ensureDirectoryExists($absoluteDir);

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid()->toString().'.'.$extension;

        $file->move($absoluteDir, $filename);

        return "{$relativeDir}/{$filename}";
    }

    private function deletePublicPath(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        File::delete(public_path($relativePath));
    }
}
