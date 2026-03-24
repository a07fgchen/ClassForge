<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import { permissionRecords, rbacPaths } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import rbac from '@/routes/rbac';
import PermissionController from '@/actions/App/Http/Controllers/PermissionController';

const props = defineProps<{
    permissions: Array<{
        id: number;
        name: string;
        description: string;
        module: {
            id: number;
            name: string;
        };
        roleCount: number;
        updated_at: string;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbac.index() },
    { title: 'Permissions', href: PermissionController.index() },
];

</script>

<template>
    <RbacPageShell :breadcrumbs="breadcrumbs" title="Permissions"
        description="Maintain a permission registry with clear descriptions, risk levels, and downstream role usage.">
        <template #actions>
            <Link :href="`${rbacPaths.permissions}/create`"
                class="inline-flex h-9 items-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground">
                New permission
            </Link>
        </template>

        <div class="overflow-hidden rounded-2xl border border-border/60 bg-background shadow-xs">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-muted/40 text-muted-foreground">
                        <tr>
                            <th class="px-5 py-4 font-medium">Permission</th>
                            <th class="px-5 py-4 font-medium">Module</th>
                            <th class="px-5 py-4 font-medium">Roles</th>
                            <th class="px-5 py-4 font-medium">Updated</th>
                            <th class="px-5 py-4 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="permission in props.permissions" :key="permission.id"
                            class="border-t border-border/50 align-top">
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ permission.name }}</p>
                                <p class="mt-2 max-w-xl text-muted-foreground">
                                    {{ permission.description }}
                                </p>
                            </td>
                            <td class="px-5 py-4">{{ permission.module.name }}</td>
                            <td class="px-5 py-4">
                                {{ permission.roleCount }}
                            </td>
                            <td class="px-5 py-4 text-muted-foreground">
                                {{ new Date(permission.updated_at).toLocaleString('zh-TW') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <Link :href="PermissionController.show(permission.id)"
                                        class="rounded-md border px-3 py-2 font-medium">
                                        View
                                    </Link>
                                    <Link :href="PermissionController.edit(permission.id)"
                                        class="rounded-md border px-3 py-2 font-medium">
                                        Edit
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </RbacPageShell>
</template>
