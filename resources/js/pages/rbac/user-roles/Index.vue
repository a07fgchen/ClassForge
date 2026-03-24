<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import { rbacPaths, userRoleRecords } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import RoleController from '@/actions/App/Http/Controllers/RoleController';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'User roles', href: RoleController.index() },
];

const statusVariant = {
    active: 'secondary',
    invited: 'outline',
    suspended: 'destructive',
} as const;
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="User role assignments"
        description="Review who currently has access, which department they belong to, and where assignment edits should begin."
    >
        <template #actions>
            <Link
                :href="`${rbacPaths.userRoles}/edit`"
                class="inline-flex h-9 items-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground"
            >
                Manage assignments
            </Link>
        </template>

        <div class="grid gap-4 lg:grid-cols-2">
            <article
                v-for="user in userRoleRecords"
                :key="user.id"
                class="rounded-2xl border border-border/60 bg-background p-5 shadow-xs"
            >
                <div
                    class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between"
                >
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-lg font-semibold tracking-tight">
                                {{ user.name }}
                            </h2>
                            <Badge :variant="statusVariant[user.status]">{{
                                user.status
                            }}</Badge>
                        </div>
                        <p class="mt-1 text-sm text-muted-foreground">
                            {{ user.email }}
                        </p>
                        <p
                            class="mt-3 text-xs tracking-[0.2em] text-muted-foreground uppercase"
                        >
                            {{ user.department }}
                        </p>
                    </div>

                    <div class="text-sm text-muted-foreground">
                        <p>Last active</p>
                        <p class="mt-1 font-medium text-foreground">
                            {{ user.lastActive }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                    <Badge
                        v-for="role in user.roles"
                        :key="role"
                        variant="outline"
                        >{{ role }}</Badge
                    >
                </div>
            </article>
        </div>
    </RbacPageShell>
</template>
