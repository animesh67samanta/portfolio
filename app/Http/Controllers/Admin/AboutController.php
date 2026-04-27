<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutRequest;
use App\Models\About;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Abouts/Index', [
            'abouts' => About::query()->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(AboutRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['photo_path'] = $this->storePublicImage($request->file('photo_path'), 'abouts');

        About::create($validated);

        return redirect()->route('admin.abouts.index')->with('success', 'About entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(AboutRequest $request, About $about): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo_path')) {
            $this->deletePublicPath($about->photo_path);
            $validated['photo_path'] = $this->storePublicImage($request->file('photo_path'), 'abouts');
        } else {
            unset($validated['photo_path']);
        }

        $about->update($validated);

        return redirect()->route('admin.abouts.index')->with('success', 'About entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about): RedirectResponse
    {
        $this->deletePublicPath($about->photo_path);
        $about->delete();

        return redirect()->route('admin.abouts.index')->with('success', 'About entry deleted successfully.');
    }

    private function storePublicImage(UploadedFile $file, string $folder): string
    {
        $relativeDir = "uploads/{$folder}";
        $absoluteDir = public_path($relativeDir);

        File::ensureDirectoryExists($absoluteDir);

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid()->toString().'.'.$extension;
        $absolutePath = "{$absoluteDir}/{$filename}";

        $file->move($absoluteDir, $filename);

        $this->resizeImage($absolutePath);

        return "{$relativeDir}/{$filename}";
    }

    private function resizeImage(string $absolutePath): void
    {
        try {
            Image::useImageDriver(ImageDriver::Vips)
                ->loadFile($absolutePath)
                ->fit(Fit::Contain, 1200, 1200)
                ->quality(85)
                ->save();
        } catch (\Throwable $vipsException) {
            // Fallback to Imagick if Vips (libvips) is not available
            try {
                Image::useImageDriver(ImageDriver::Imagick)
                    ->loadFile($absolutePath)
                    ->fit(Fit::Contain, 1200, 1200)
                    ->quality(85)
                    ->save();
            } catch (\Throwable $imagickException) {
                // Final fallback to GD
                Image::useImageDriver(ImageDriver::Gd)
                    ->loadFile($absolutePath)
                    ->fit(Fit::Contain, 1200, 1200)
                    ->quality(85)
                    ->save();
            }
        }
    }

    private function deletePublicPath(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        File::delete(public_path($relativePath));
    }
}
