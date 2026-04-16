<?php

use App\Models\Skill;

test('skill published scope returns only active skills', function () {
    Skill::query()->create([
        'name' => 'Vue',
        'slug' => 'vue',
        'status' => 'active',
    ]);

    Skill::query()->create([
        'name' => 'jQuery',
        'slug' => 'jquery',
        'status' => 'inactive',
    ]);

    $skills = Skill::published()->pluck('slug')->all();

    expect($skills)->toBe(['vue']);
});
