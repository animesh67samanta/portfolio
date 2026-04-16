<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::query()->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(TestimonialRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['avatar_path'] = $this->storePublicImage($request->file('avatar_path'), 'testimonials');

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar_path')) {
            $this->deletePublicPath($testimonial->avatar_path);
            $validated['avatar_path'] = $this->storePublicImage($request->file('avatar_path'), 'testimonials');
        } else {
            unset($validated['avatar_path']);
        }

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->deletePublicPath($testimonial->avatar_path);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
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
