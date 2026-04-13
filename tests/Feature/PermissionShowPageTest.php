<?php

use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('permission show page renders chinese copy', function () {
    $user = User::factory()->create();

    $module = new Module;
    $module->forceFill([
        'name' => 'Commerce',
        'description' => 'Commerce module',
    ]);
    $module->save();

    $permission = new Permission;
    $permission->forceFill([
        'name' => 'payments.refund',
        'description' => 'Issue partial or full refunds and add refund notes.',
        'slug' => 'payments.refund',
        'module_id' => $module->id,
    ]);
    $permission->save();

    actingAs($user);

    get(route('rbac.permissions.show', $permission))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('rbac/permissions/Show')
            ->where('permission.name', 'payments.refund')
            ->where('permission.module.name', 'Commerce')
        );

    expect(file_get_contents(resource_path('js/pages/rbac/permissions/Show.vue')))
        ->toContain('權限代碼')
        ->toContain('最後更新')
        ->toContain('作業影響')
        ->toContain('繼承此權限的角色')
        ->toContain('目前沒有角色繼承此權限。');

    expect(file_get_contents(resource_path('js/pages/rbac/components/RbacPageShell.vue')))
        ->toContain('RBAC 工作區');
});
