<?php

use App\Models\Tenant;
use Database\Seeders\TenantSeeder;

test('tenant seeder creates tenant and platform scope tenants', function () {
    $this->seed(TenantSeeder::class);

    expect(Tenant::query()->count())->toBe(2)
        ->and(Tenant::query()->where('plan_level', 'tenant_scope')->exists())->toBeTrue()
        ->and(Tenant::query()->where('plan_level', 'platform_scope')->exists())->toBeTrue();
});
