<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use App\Services\Admin\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TestimonialService $testimonialService): Response
    {
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => $testimonialService->list(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(TestimonialRequest $request, TestimonialService $testimonialService): RedirectResponse
    {
        $testimonialService->create($request->validated());

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial, TestimonialService $testimonialService): RedirectResponse
    {
        $testimonialService->update($testimonial, $request->validated());

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial, TestimonialService $testimonialService): RedirectResponse
    {
        $testimonialService->delete($testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }
}
