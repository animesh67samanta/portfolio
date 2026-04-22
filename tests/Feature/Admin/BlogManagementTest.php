<?php

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

test('admin can manage blogs', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->createOne();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->post(route('admin.blogs.store', absolute: false), [
            'title' => 'First Blog',
            'slug' => 'first-blog',
            'excerpt' => 'Excerpt',
            'content' => 'Blog content',
            'featured_image' => UploadedFile::fake()->image('featured.jpg'),
            'status' => 'draft',
            'published_at' => null,
        ])
        ->assertRedirect(route('admin.blogs.index', absolute: false));

    $blog = Blog::query()->firstOrFail();

    expect($blog->slug)->toBe('first-blog');

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->patch(route('admin.blogs.update', ['blog' => $blog], absolute: false), [
            'title' => 'Updated Blog',
            'slug' => 'updated-blog',
            'excerpt' => 'New excerpt',
            'content' => 'Updated content',
            'status' => 'published',
            'published_at' => now()->toDateTimeString(),
        ])
        ->assertRedirect(route('admin.blogs.index', absolute: false));

    $blog->refresh();

    expect($blog->title)->toBe('Updated Blog')
        ->and($blog->slug)->toBe('updated-blog');

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->delete(route('admin.blogs.destroy', ['blog' => $blog], absolute: false))
        ->assertRedirect(route('admin.blogs.index', absolute: false));

    expect(Blog::query()->count())->toBe(0);
});
