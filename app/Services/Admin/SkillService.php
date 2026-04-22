<?php

namespace App\Services\Admin;

use App\Models\Skill;
use App\Services\ImageService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SkillService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    public function list(int $perPage = 10): LengthAwarePaginator
    {
        return Skill::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $data): Skill
    {
        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);

        if (isset($data['icon'])) {
            $data['icon'] = $this->imageService->storeImage($data['icon'], 'skills/icons');
        }

        return Skill::query()->create($data);
    }

    public function update(Skill $skill, array $data): Skill
    {
        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);

        if (array_key_exists('icon', $data) && $data['icon'] instanceof \Illuminate\Http\UploadedFile) {
            $this->imageService->deleteImage($skill->icon);
            $data['icon'] = $this->imageService->storeImage($data['icon'], 'skills/icons');
        } elseif (array_key_exists('icon', $data) && is_null($data['icon'])) {
            // $this->imageService->deleteImage($skill->icon);
            unset($data['icon']);
        } else {
            unset($data['icon']);
        }

        $skill->update($data);

        return $skill;
    }

    public function delete(Skill $skill): void
    {
        $this->imageService->deleteImage($skill->icon);

        $skill->delete();
    }
}

