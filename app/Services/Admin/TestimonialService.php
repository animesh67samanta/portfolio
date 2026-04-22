<?php

namespace App\Services\Admin;

use App\Models\Testimonial;
use App\Services\ImageService;
use Illuminate\Pagination\LengthAwarePaginator;

class TestimonialService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    public function list(int $perPage = 10): LengthAwarePaginator
    {
        return Testimonial::query()->latest()->paginate($perPage);
    }

    public function create(array $data): Testimonial
    {
        $data['avatar_path'] = $this->imageService->storeImage($data['avatar_path'], 'testimonials');

        return Testimonial::query()->create($data);
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        if (array_key_exists('avatar_path', $data) && $data['avatar_path'] instanceof \Illuminate\Http\UploadedFile) {
            $this->imageService->deleteImage($testimonial->avatar_path);
            $data['avatar_path'] = $this->imageService->storeImage($data['avatar_path'], 'testimonials');
        } elseif (array_key_exists('avatar_path', $data) && is_null($data['avatar_path'])) {
            // $this->imageService->deleteImage($testimonial->avatar_path);
            unset($data['avatar_path']);
        } else {
            unset($data['avatar_path']);
        }

        $testimonial->update($data);

        return $testimonial;
    }

    public function delete(Testimonial $testimonial): void
    {
        $this->imageService->deleteImage($testimonial->avatar_path);
        $testimonial->delete();
    }
}
