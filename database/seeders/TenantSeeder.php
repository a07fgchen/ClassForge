<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Tenant Scope',
                'status' => 'active',
                'plan_level' => 'free',
            ],
            [
                'name' => 'Platform Scope',
                'status' => 'active',
                'plan_level' => 'basic',
            ],
        ];

        foreach ($tenants as $tenantData) {
            $tenant = Tenant::query()->firstOrNew(['name' => $tenantData['name']]);

            if (! $tenant->exists) {
                $tenant->id = (string) Str::uuid();
            }

            $tenant->status = $tenantData['status'];
            $tenant->plan_level = $tenantData['plan_level'];
            $tenant->save();
        }
    }
}
