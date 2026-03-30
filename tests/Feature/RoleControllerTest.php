<?php

use App\Models\User;

test('storing a role redirects to roles index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('rbac.roles.store'), [
        'name' => 'Test Role',
        'scope' => 'tenant',
        'permissions' => [],
    ]);

    $response->assertRedirect(route('rbac.roles.index'));
});
