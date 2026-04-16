<?php

use App\Models\Banner;

test('banner published scope requires published status and published_at', function () {
    Banner::query()->create([
        'title' => 'Draft but has date',
        'subtitle' => null,
        'image_path' => 'uploads/banners/a.jpg',
        'cta_text' => null,
        'cta_url' => null,
        'status' => 'draft',
        'sort_order' => 0,
        'published_at' => now(),
    ]);

    Banner::query()->create([
        'title' => 'Published but no date',
        'subtitle' => null,
        'image_path' => 'uploads/banners/b.jpg',
        'cta_text' => null,
        'cta_url' => null,
        'status' => 'published',
        'sort_order' => 0,
        'published_at' => null,
    ]);

    Banner::query()->create([
        'title' => 'Published with date',
        'subtitle' => null,
        'image_path' => 'uploads/banners/c.jpg',
        'cta_text' => null,
        'cta_url' => null,
        'status' => 'published',
        'sort_order' => 0,
        'published_at' => now(),
    ]);

    $titles = Banner::published()->pluck('title')->all();

    expect($titles)->toBe(['Published with date']);
});
