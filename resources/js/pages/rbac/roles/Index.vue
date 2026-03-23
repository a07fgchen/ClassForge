<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import { rbacPaths, roleRecords } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import rbac from '@/routes/rbac';

interface Role {
    id: number;
    name: string;
    description: string;
    scope: string;
    status: 'draft' | 'custom' | 'managed';
    permissionCount: number;
    userCount: number;
    updatedAt: string;
}

const props = defineProps<{
    roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'Roles', href: rbac.roles() },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="Roles"
        description="Browse all role definitions, inspect their blast radius, and route reviewers to create or edit flows."
    >
        <template #actions>
            <Link
                :href="rbac.roles.create()"
                class="inline-flex h-9 items-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground"
            >
                New role
            </Link>
        </template>

        <div
            class="overflow-hidden rounded-2xl border border-border/60 bg-background shadow-xs"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-muted/40 text-muted-foreground">
                        <tr>
                            <th class="px-5 py-4 font-medium">Role</th>
                            <th class="px-5 py-4 font-medium">Scope</th>
                            <th class="px-5 py-4 font-medium">Permissions</th>
                            <th class="px-5 py-4 font-medium">Users</th>
                            <th class="px-5 py-4 font-medium">Updated</th>
                            <th class="px-5 py-4 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="role in props.roles"
                            :key="role.id"
                            class="border-t border-border/50 align-top"
                        >
                            <td class="px-5 py-4">
                                <div class="space-y-2">
                                    <div
                                        class="flex flex-wrap items-center gap-2"
                                    >
                                        <p class="font-medium">
                                            {{ role.name }}
                                        </p>
                                        <Badge
                                            :variant="
                                                role.status === 'draft'
                                                    ? 'outline'
                                                    : 'secondary'
                                            "
                                            >{{ role.status }}</Badge
                                        >
                                    </div>
                                    <p class="max-w-xl text-muted-foreground">
                                        {{ role.description }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 capitalize">
                                {{ role.scope }}
                            </td>
                            <td class="px-5 py-4">
                                {{ role.permissionCount }}
                            </td>
                            <td class="px-5 py-4">{{ role.userCount }}</td>
                            <td class="px-5 py-4 text-muted-foreground">
                                {{ role.updatedAt }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <Link
                                        :href="`${rbacPaths.roles}/${role.id}`"
                                        class="rounded-md border px-3 py-2 font-medium"
                                    >
                                        View
                                    </Link>
                                    <Link
                                        :href="`${rbacPaths.roles}/${role.id}/edit`"
                                        class="rounded-md border px-3 py-2 font-medium"
                                    >
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
