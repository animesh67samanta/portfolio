<?php

use App\Models\Skill;
use App\Models\User;
use Tests\TestCase;

test('admin can manage skills', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->createOne();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->get(route('admin.skills.index', absolute: false))
        ->assertOk();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->post(route('admin.skills.store', absolute: false), [
            'name' => 'Vue',
            'slug' => 'vue',
            'proficiency' => 90,
            'icon' => 'ri-vuejs-fill',
            'status' => 'active',
            'sort_order' => 1,
        ])
        ->assertRedirect(route('admin.skills.index', absolute: false));

    $skill = Skill::query()->firstOrFail();

    expect($skill->name)->toBe('Vue');

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->patch(route('admin.skills.update', ['skill' => $skill], absolute: false), [
            'name' => 'Vue.js',
            'slug' => 'vue-js',
            'proficiency' => 95,
            'icon' => 'ri-vuejs-line',
            'status' => 'inactive',
            'sort_order' => 2,
        ])
        ->assertRedirect(route('admin.skills.index', absolute: false));

    $skill->refresh();

    expect($skill->name)->toBe('Vue.js')
        ->and($skill->slug)->toBe('vue-js')
        ->and($skill->proficiency)->toBe(95)
        ->and($skill->status)->toBe('inactive')
        ->and($skill->sort_order)->toBe(2);

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->delete(route('admin.skills.destroy', ['skill' => $skill], absolute: false))
        ->assertRedirect(route('admin.skills.index', absolute: false));

    expect(Skill::query()->count())->toBe(0);
});
