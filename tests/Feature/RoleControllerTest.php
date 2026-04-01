<?php

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use function Pest\Laravel\actingAs;

test('create role page is displayed with grouped permissions', function () {
    $user = User::factory()->create();

    $module = new Module();
    $module->name = 'Courses';
    $module->description = 'Course permissions';
    $module->save();

    $permission = Permission::query()->create([
        'name' => 'View courses',
        'slug' => 'courses.view',
        'description' => 'View course records',
        'module_id' => $module->id,
    ]);

    actingAs($user);

    $this
        ->get(route('rbac.roles.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('rbac/roles/Create')
            ->where('permissions.Courses.0.id', $permission->id)
            ->where('permissions.Courses.0.name', $permission->name),
        );
});

test('storing a role redirects to roles index', function () {
    $user = User::factory()->create();

    $permission = Permission::query()->create([
        'name' => 'Manage bookings',
        'slug' => 'bookings.manage',
        'description' => 'Manage booking records',
    ]);

    actingAs($user);

    $response = $this->post(route('rbac.roles.store'), [
        'display_name' => 'Test Role',
        'description' => 'Role used for controller coverage.',
        'scope' => 1,
        'is_protected' => false,
        'permissions' => [$permission->id],
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('rbac.roles.index'));

    $role = Role::query()
        ->with('permissions:id')
        ->where('display_name', 'Test Role')
        ->first();

    expect($role)->not->toBeNull();
    expect($role?->slug)->toBe('test-role');
    expect($role?->scope)->toBe(1);
    expect($role?->is_protected)->toBeFalse();
    expect($role?->permissions->pluck('id')->all())->toBe([$permission->id]);
});
