<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SkillRequest;
use App\Models\Skill;
use App\Services\Admin\SkillService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SkillService $skillService): Response
    {
        return Inertia::render('Admin/Skills/Index', [
            'skills' => $skillService->list(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(SkillRequest $request, SkillService $skillService): RedirectResponse
    {
        $skillService->create($request->validated());

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(SkillRequest $request, Skill $skill, SkillService $skillService): RedirectResponse
    {
        $skillService->update($skill, $request->validated());

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill, SkillService $skillService): RedirectResponse
    {
        $skillService->delete($skill);

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }
}

