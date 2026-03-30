<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Courses',
                'description' => 'Course catalog, sessions, and enrollment management.',
            ],
            [
                'name' => 'Bookings',
                'description' => 'Reservations, scheduling, and waitlist workflows.',
            ],
            [
                'name' => 'Commerce',
                'description' => 'Payments, invoices, and refund handling.',
            ],
            [
                'name' => 'Reports',
                'description' => 'Operational and financial reporting access.',
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::query()->firstOrNew(['name' => $moduleData['name']]);
            $module->description = $moduleData['description'];
            $module->save();
        }
    }
}
