<?php

namespace App\Services\Admin;

use App\Models\Blog;
use App\Services\ImageService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BlogService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    public function list(int $perPage = 10): LengthAwarePaginator
    {
        return Blog::query()->latest()->paginate($perPage);
    }

    public function create(array $data): Blog
    {
        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);
        $data['featured_image'] = $this->imageService->storeImage($data['featured_image'], 'blogs');

        if (isset($data['cover_image'])) {
            $data['cover_image'] = $this->imageService->storeImage($data['cover_image'], 'blogs');
        }

        return Blog::query()->create($data);
    }

    public function update(Blog $blog, array $data): Blog
    {
        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        if (array_key_exists('featured_image', $data) && $data['featured_image'] instanceof \Illuminate\Http\UploadedFile) {
            $this->imageService->deleteImage($blog->featured_image);
            $data['featured_image'] = $this->imageService->storeImage($data['featured_image'], 'blogs');
        } elseif (array_key_exists('featured_image', $data) && is_null($data['featured_image'])) {
            // $this->imageService->deleteImage($blog->featured_image);
            unset($data['featured_image']);
        } else {
            unset($data['featured_image']);
        }

        if (array_key_exists('cover_image', $data) && $data['cover_image'] instanceof \Illuminate\Http\UploadedFile) {
            $this->imageService->deleteImage($blog->cover_image);
            $data['cover_image'] = $this->imageService->storeImage($data['cover_image'], 'blogs');
        } elseif (array_key_exists('cover_image', $data) && is_null($data['cover_image'])) {
            // $this->imageService->deleteImage($blog->cover_image);
            unset($data['cover_image']);
        } else {
            unset($data['cover_image']);
        }

        $blog->update($data);

        return $blog;
    }

    public function delete(Blog $blog): void
    {
        $this->imageService->deleteImage($blog->featured_image);
        $this->imageService->deleteImage($blog->cover_image);

        $blog->delete();
    }
}
