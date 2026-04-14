<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guests are redirected from tenant invitation workspace', function () {
    get(route('tenant.invitations.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can view tenant invitation workspace', function () {
    $user = User::factory()->create();

    actingAs($user);

    get(route('tenant.invitations.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tenant/invitations/Index')
        );
});
