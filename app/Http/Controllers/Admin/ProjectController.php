<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Services\Admin\ProjectService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProjectService $projectService): Response
    {
        $data = $projectService->list();

        return Inertia::render('Admin/Projects/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ProjectService $projectService): Response
    {
        $data = $projectService->list(perPage: 1);

        return Inertia::render('Admin/Projects/Create', [
            'skills' => $data['skills'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request, ProjectService $projectService): RedirectResponse
    {
        $projectService->create($request->validated());

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(ProjectRequest $request, Project $project, ProjectService $projectService): RedirectResponse
    {
        $projectService->update($project, $request->validated());

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectService $projectService): RedirectResponse
    {
        $projectService->delete($project);

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
