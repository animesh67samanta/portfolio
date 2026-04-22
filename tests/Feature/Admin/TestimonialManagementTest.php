<?php

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

test('admin can manage testimonials', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->createOne();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->post(route('admin.testimonials.store', absolute: false), [
            'client_name' => 'Jane Doe',
            'client_role' => 'CTO',
            'company' => 'Acme Inc.',
            'avatar_path' => UploadedFile::fake()->image('avatar.jpg'),
            'quote' => 'Great work',
            'rating' => 5,
            'status' => 'active',
            'sort_order' => 1,
        ])
        ->assertRedirect(route('admin.testimonials.index', absolute: false));

    $testimonial = Testimonial::query()->firstOrFail();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->patch(route('admin.testimonials.update', ['testimonial' => $testimonial], absolute: false), [
            'client_name' => 'Jane D.',
            'client_role' => 'VP Engineering',
            'company' => 'Acme Inc.',
            'quote' => 'Amazing delivery',
            'rating' => 4,
            'status' => 'inactive',
            'sort_order' => 2,
        ])
        ->assertRedirect(route('admin.testimonials.index', absolute: false));

    $testimonial->refresh();

    expect($testimonial->client_name)->toBe('Jane D.')
        ->and($testimonial->status)->toBe('inactive');

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->delete(route('admin.testimonials.destroy', ['testimonial' => $testimonial], absolute: false))
        ->assertRedirect(route('admin.testimonials.index', absolute: false));

    expect(Testimonial::query()->count())->toBe(0);
});
