<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Store uploaded image in public/uploads/{folder}.
     */
    public function storeImage(UploadedFile $file, string $folder): string
    {
        $relativeDir = "uploads/{$folder}";
        $absoluteDir = public_path($relativeDir);

        File::ensureDirectoryExists($absoluteDir);

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid()->toString().'.'.$extension;

        $file->move($absoluteDir, $filename);

        return "{$relativeDir}/{$filename}";
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
