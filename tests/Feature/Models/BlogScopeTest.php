<?php

use App\Models\Blog;

test('blog published scope requires published status and published_at', function () {
    Blog::query()->create([
        'title' => 'Draft but has date',
        'slug' => 'draft-has-date',
        'content' => 'content',
        'status' => 'draft',
        'published_at' => now(),
    ]);

    Blog::query()->create([
        'title' => 'Published but no date',
        'slug' => 'published-no-date',
        'content' => 'content',
        'status' => 'published',
        'published_at' => null,
    ]);

    Blog::query()->create([
        'title' => 'Published with date',
        'slug' => 'published-with-date',
        'content' => 'content',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $slugs = Blog::published()->pluck('slug')->all();

    expect($slugs)->toBe(['published-with-date']);
});
