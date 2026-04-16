<?php

use App\Models\User;
use Tests\TestCase;

test('non admin users cannot access admin dashboard', function () {
    /** @var User $user */
    $user = User::factory()->createOne([
        'is_admin' => false,
    ]);

    /** @var TestCase $this */
    $this->actingAs($user)
        ->get(route('admin.dashboard', absolute: false))
        ->assertForbidden();
});

test('admin users can access admin dashboard', function () {
    /** @var User $admin */
    $admin = User::factory()->admin()->createOne();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->get(route('admin.dashboard', absolute: false))
        ->assertOk();
});
