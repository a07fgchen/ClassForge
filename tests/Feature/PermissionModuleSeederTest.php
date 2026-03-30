<?php

use App\Models\Module;
use App\Models\Permission;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\PermissionSeeder;

test('module and permission seeders populate permission matrix', function () {
    $this->seed([
        ModuleSeeder::class,
        PermissionSeeder::class,
    ]);

    expect(Module::query()->count())->toBe(4)
        ->and(Permission::query()->count())->toBe(9);

    $coursesModuleId = Module::query()->where('name', 'Courses')->value('id');

    expect(Permission::query()->where('slug', 'COURSES.VIEW')->value('module_id'))
        ->toBe($coursesModuleId)
        ->and(Permission::query()->where('slug', 'PAYMENTS.REFUND')->exists())
        ->toBeTrue()
        ->and(Permission::query()->where('slug', 'REPORTS.VIEW')->exists())
        ->toBeTrue();
});
