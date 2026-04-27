<?php

namespace App\Services\Admin;

use App\Models\Project;
use App\Models\Skill;
use App\Services\ImageService;

class ProjectService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    /**
     * Get projects for index with skills.
     */
    public function list(int $perPage = 10): array
    {
        return [
            'projects' => Project::with('skills')->latest()->paginate($perPage),
            'skills' => Skill::orderBy('name')->get(['id', 'name']),
        ];
    }

    /**
     * Create project.
     */
    public function create(array $data): Project
    {
        if (isset($data['thumbnail_path'])) {
            $data['thumbnail_path'] = $this->imageService->storeImage($data['thumbnail_path'], 'projects', 1200, 800);
        }

        $project = Project::create($data);
        $project->skills()->sync($data['skill_ids'] ?? []);

        return $project;
    }

    /**
     * Update project.
     */
    public function update(Project $project, array $data): Project
    {
        if (array_key_exists('thumbnail_path', $data) && $data['thumbnail_path'] instanceof \Illuminate\Http\UploadedFile) {
            $this->imageService->deleteImage($project->thumbnail_path);
            $data['thumbnail_path'] = $this->imageService->storeImage($data['thumbnail_path'], 'projects');
        } elseif (array_key_exists('thumbnail_path', $data) && is_null($data['thumbnail_path'])) {
            // $this->imageService->deleteImage($project->thumbnail_path);
            unset($data['thumbnail_path']);
        } else {
            unset($data['thumbnail_path']);
        }

        $project->update($data);
        $project->skills()->sync($data['skill_ids'] ?? []);

        return $project;
    }

    /**
     * Delete project.
     */
    public function delete(Project $project): void
    {
        $this->imageService->deleteImage($project->thumbnail_path);
        $project->delete();
    }
}
