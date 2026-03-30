<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduleByName = Module::query()->get()->keyBy('name');
        $permissions = [
            [
                'module' => 'Courses',
                'name' => 'View courses',
                'description' => 'Read course catalog, schedules, and enrollment limits.',
                'slug' => 'COURSES.VIEW',
            ],
            [
                'module' => 'Courses',
                'name' => 'Manage courses',
                'description' => 'Create, edit, publish, and archive courses.',
                'slug' => 'COURSES.MANAGE',
            ],
            [
                'module' => 'Courses',
                'name' => 'Manage sessions',
                'description' => 'Adjust capacity, instructors, and cut-off times.',
                'slug' => 'SESSIONS.MANAGE',
            ],
            [
                'module' => 'Bookings',
                'name' => 'View bookings',
                'description' => 'Inspect reservation status, attendance, and notes.',
                'slug' => 'BOOKINGS.VIEW',
            ],
            [
                'module' => 'Bookings',
                'name' => 'Manage bookings',
                'description' => 'Create, cancel, reschedule, and confirm reservations.',
                'slug' => 'BOOKINGS.MANAGE',
            ],
            [
                'module' => 'Bookings',
                'name' => 'Promote waitlist',
                'description' => 'Move members from waitlist into open session slots.',
                'slug' => 'WAITLIST.PROMOTE',
            ],
            [
                'module' => 'Commerce',
                'name' => 'View payments',
                'description' => 'See payment status, invoices, and refund history.',
                'slug' => 'PAYMENTS.VIEW',
            ],
            [
                'module' => 'Commerce',
                'name' => 'Process refunds',
                'description' => 'Issue partial or full refunds and add refund notes.',
                'slug' => 'PAYMENTS.REFUND',
            ],
            [
                'module' => 'Reports',
                'name' => 'View reports',
                'description' => 'Access revenue, attendance, and instructor reports.',
                'slug' => 'REPORTS.VIEW',
            ],
        ];

        foreach ($permissions as $permissionData) {
            $module = $moduleByName->get($permissionData['module']);

            if (! $module) {
                continue;
            }

            Permission::query()->updateOrCreate(
                ['slug' => $permissionData['slug']],
                [
                    'module_id' => $module->id,
                    'name' => $permissionData['name'],
                    'description' => $permissionData['description'],
                ]
            );
        }
    }
}
