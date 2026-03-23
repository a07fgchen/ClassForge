<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import RbacStatCard from '@/pages/rbac/components/RbacStatCard.vue';
import {
    permissionRecords,
    rbacPaths,
    roleRecords,
    userRoleRecords,
} from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import rbac from '@/routes/rbac';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'RBAC',
        href: rbacPaths.overview,
    },
];

const modules = [
    ...new Set(permissionRecords.map((permission) => permission.module)),
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="Access control overview"
        description="Manage roles, permissions, and user assignments from a single workspace so future backend wiring has a stable UI target."
    >
        <template #actions>
            <Link
                :href="rbac.roles()"
                class="inline-flex h-9 items-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground"
            >
                Open roles
            </Link>
            <Link
                :href="rbac.permissions()"
                class="inline-flex h-9 items-center rounded-md border px-4 text-sm font-medium"
            >
                Open permissions
            </Link>
        </template>

        <div class="space-y-6">
            <div class="grid gap-4 lg:grid-cols-4">
                <RbacStatCard
                    label="Roles"
                    :value="roleRecords.length"
                    hint="System, custom, and draft role definitions."
                />
                <RbacStatCard
                    label="Permissions"
                    :value="permissionRecords.length"
                    hint="Mapped to business modules for auditing."
                />
                <RbacStatCard
                    label="Assigned users"
                    :value="userRoleRecords.length"
                    hint="Staff and platform operators with active access."
                />
                <RbacStatCard
                    label="Modules"
                    :value="modules.length"
                    hint="Coverage across courses, commerce, reporting, and governance."
                />
            </div>

            <div
                class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]"
            >
                <section
                    class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
                >
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight">
                                Role health
                            </h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Focus first on high-impact roles and drafts
                                waiting for review.
                            </p>
                        </div>
                        <Badge variant="secondary"
                            >{{ roleRecords.length }} entries</Badge
                        >
                    </div>

                    <div class="mt-5 space-y-4">
                        <article
                            v-for="role in roleRecords"
                            :key="role.id"
                            class="rounded-2xl border border-border/50 bg-muted/20 p-5"
                        >
                            <div
                                class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between"
                            >
                                <div class="space-y-2">
                                    <div
                                        class="flex flex-wrap items-center gap-2"
                                    >
                                        <h3 class="text-base font-semibold">
                                            {{ role.name }}
                                        </h3>
                                        <Badge
                                            :variant="
                                                role.status === 'draft'
                                                    ? 'outline'
                                                    : 'secondary'
                                            "
                                            >{{ role.status }}</Badge
                                        >
                                        <Badge variant="outline">{{
                                            role.scope
                                        }}</Badge>
                                    </div>
                                    <p class="text-sm text-muted-foreground">
                                        {{ role.description }}
                                    </p>
                                </div>
                                <div
                                    class="grid grid-cols-2 gap-3 text-sm md:min-w-52"
                                >
                                    <div
                                        class="rounded-xl border border-border/50 bg-background p-3"
                                    >
                                        <p class="text-muted-foreground">
                                            Permissions
                                        </p>
                                        <p class="mt-1 font-semibold">
                                            {{ role.permissionCount }}
                                        </p>
                                    </div>
                                    <div
                                        class="rounded-xl border border-border/50 bg-background p-3"
                                    >
                                        <p class="text-muted-foreground">
                                            Users
                                        </p>
                                        <p class="mt-1 font-semibold">
                                            {{ role.userCount }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                <section class="space-y-6">
                    <article
                        class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
                    >
                        <h2 class="text-lg font-semibold tracking-tight">
                            Quick navigation
                        </h2>
                        <div class="mt-4 grid gap-3">
                            <Link
                                :href="rbacPaths.roles"
                                class="rounded-2xl border border-border/60 bg-muted/20 px-4 py-4 text-sm font-medium transition hover:bg-muted/40"
                            >
                                Roles catalog
                            </Link>
                            <Link
                                :href="rbacPaths.permissions"
                                class="rounded-2xl border border-border/60 bg-muted/20 px-4 py-4 text-sm font-medium transition hover:bg-muted/40"
                            >
                                Permission registry
                            </Link>
                            <Link
                                :href="rbacPaths.userRoles"
                                class="rounded-2xl border border-border/60 bg-muted/20 px-4 py-4 text-sm font-medium transition hover:bg-muted/40"
                            >
                                User assignments
                            </Link>
                        </div>
                    </article>

                    <article
                        class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
                    >
                        <h2 class="text-lg font-semibold tracking-tight">
                            High-risk permissions
                        </h2>
                        <div class="mt-4 space-y-3">
                            <div
                                v-for="permission in permissionRecords.filter(
                                    (item) => item.risk === 'high',
                                )"
                                :key="permission.id"
                                class="rounded-xl border border-border/50 bg-muted/20 p-4"
                            >
                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <div>
                                        <p class="font-medium">
                                            {{ permission.name }}
                                        </p>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ permission.description }}
                                        </p>
                                    </div>
                                    <Badge variant="destructive">high</Badge>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>
            </div>
        </div>
    </RbacPageShell>
</template>
