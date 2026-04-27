<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;

class ImageService
{
    /**
     * Store uploaded image in public/uploads/{folder} and resize it.
     */
    public function storeImage(UploadedFile $file, string $folder, int $maxWidth = 1920, int $maxHeight = 1920): string
    {
        $relativeDir = "uploads/{$folder}";
        $absoluteDir = public_path($relativeDir);

        File::ensureDirectoryExists($absoluteDir);

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid()->toString().'.'.$extension;
        $absolutePath = "{$absoluteDir}/{$filename}";

        $file->move($absoluteDir, $filename);

        $this->resizeImage($absolutePath, $maxWidth, $maxHeight);

        return "{$relativeDir}/{$filename}";
    }

    /**
     * Resize an image using spatie/image with Vips driver, falling back to Imagick then GD.
     */
    public function resizeImage(string $absolutePath, int $maxWidth = 1920, int $maxHeight = 1920): void
    {
        try {
            Image::useImageDriver(ImageDriver::Vips)
                ->loadFile($absolutePath)
                ->fit(Fit::Contain, $maxWidth, $maxHeight)
                ->quality(85)
                ->save();
        } catch (\Throwable $vipsException) {
            try {
                Image::useImageDriver(ImageDriver::Imagick)
                    ->loadFile($absolutePath)
                    ->fit(Fit::Contain, $maxWidth, $maxHeight)
                    ->quality(85)
                    ->save();
            } catch (\Throwable $imagickException) {
                Image::useImageDriver(ImageDriver::Gd)
                    ->loadFile($absolutePath)
                    ->fit(Fit::Contain, $maxWidth, $maxHeight)
                    ->quality(85)
                    ->save();
            }
        }
    }

    /**
     * Delete image from public path.
     */
    public function deleteImage(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        File::delete(public_path($relativePath));
    }
}
