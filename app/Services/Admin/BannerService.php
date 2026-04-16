<?php

namespace App\Services\Admin;

use App\Models\Banner;
use App\Services\ImageService;
use Illuminate\Pagination\LengthAwarePaginator;

class BannerService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    /**
     * Get banners for index.
     */
    public function list(int $perPage = 10): LengthAwarePaginator
    {
        return Banner::latest()->paginate($perPage);
    }

    /**
     * Create banner.
     */
    public function create(array $data): Banner
    {
        $data['image_path'] = $this->imageService->storeImage($data['image_path'], 'banners');

        return Banner::create($data);
    }

    /**
     * Update banner.
     */
    public function update(Banner $banner, array $data): Banner
    {
        if (isset($data['image_path'])) {
            $this->imageService->deleteImage($banner->image_path);
            $data['image_path'] = $this->imageService->storeImage($data['image_path'], 'banners');
        } else {
            unset($data['image_path']);
        }

        $banner->update($data);

        return $banner;
    }

    /**
     * Delete banner.
     */
    public function delete(Banner $banner): void
    {
        $this->imageService->deleteImage($banner->image_path);
        $banner->delete();
    }
}
