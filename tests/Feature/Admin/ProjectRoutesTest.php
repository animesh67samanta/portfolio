<?php

use App\Models\User;
use Tests\TestCase;

test('admin can view the project create page', function () {
    /** @var User $user */
    $user = User::factory()->admin()->createOne();

    /** @var TestCase $this */
    $response = $this->actingAs($user)->get(route('admin.projects.create', absolute: false));

    $response->assertStatus(200);
});
