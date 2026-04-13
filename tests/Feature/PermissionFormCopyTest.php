<?php

use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('permission create and edit pages use chinese copy', function () {
    $user = User::factory()->create();

    $module = new Module;
    $module->forceFill([
        'name' => 'Identity',
        'description' => 'Identity module',
    ]);
    $module->save();

    $permission = new Permission;
    $permission->forceFill([
        'name' => 'roles.manage',
        'description' => 'Create, edit, and retire custom roles.',
        'slug' => 'roles.manage',
        'module_id' => $module->id,
    ]);
    $permission->save();

    actingAs($user);

    get(route('rbac.permissions.create'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('rbac/permissions/Create')
            ->has('modules', 1)
        );

    get(route('rbac.permissions.edit', $permission))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('rbac/permissions/Edit')
            ->where('permission.name', 'roles.manage')
            ->has('modules', 1)
        );

    expect(file_get_contents(resource_path('js/pages/rbac/components/PermissionForm.vue')))
        ->toContain('權限唯一識別碼')
        ->toContain('select')
        ->toContain('id="permission-module"')
        ->toContain('v-for="module in props.modules"')
        ->toContain('建立權限')
        ->toContain('更新權限');

    expect(file_get_contents(resource_path('js/pages/rbac/permissions/Create.vue')))
        ->toContain('title="建立權限"')
        ->toContain('定義新的權限代碼');

    expect(file_get_contents(resource_path('js/pages/rbac/permissions/Edit.vue')))
        ->toContain('title="編輯權限"')
        ->toContain('調整權限定義與描述');
});
