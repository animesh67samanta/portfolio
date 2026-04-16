<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use App\Services\Admin\BannerService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BannerService $bannerService): Response
    {
        $banners = $bannerService->list();

        return Inertia::render('Admin/Banners/Index', [
            'banners' => $banners,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(BannerRequest $request, BannerService $bannerService): RedirectResponse
    {
        $bannerService->create($request->validated());

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function update(BannerRequest $request, Banner $banner, BannerService $bannerService): RedirectResponse
    {
        $bannerService->update($banner, $request->validated());

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner, BannerService $bannerService): RedirectResponse
    {
        $bannerService->delete($banner);

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
