<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import {
    permissionOptions,
    rbacPaths,
    roleRecords,
    userRoleRecords,
} from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';

const role = roleRecords[2];

const permissions = permissionOptions.filter((permission) =>
    role.permissions.includes(permission.key),
);
const members = userRoleRecords.filter((user) =>
    user.roles.includes(role.name),
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'Roles', href: rbacPaths.roles },
    { title: role.name, href: `${rbacPaths.roles}/${role.id}` },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        :title="role.name"
        :description="role.description"
    >
        <div
            class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]"
        >
            <section
                class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <div class="flex flex-wrap items-center gap-2">
                    <Badge
                        :variant="
                            role.status === 'custom' ? 'secondary' : 'outline'
                        "
                        >{{ role.status }}</Badge
                    >
                    <Badge variant="outline">{{ role.scope }}</Badge>
                    <Badge variant="outline"
                        >{{ role.permissionCount }} permissions</Badge
                    >
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">
                            Assigned members
                        </p>
                        <p class="mt-2 text-2xl font-semibold">
                            {{ members.length }}
                        </p>
                    </article>
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">Updated at</p>
                        <p class="mt-2 text-2xl font-semibold">
                            {{ role.updatedAt }}
                        </p>
                    </article>
                </div>

                <div class="space-y-4">
                    <h2 class="text-lg font-semibold tracking-tight">
                        Permission coverage
                    </h2>
                    <div class="grid gap-4 lg:grid-cols-2">
                        <article
                            v-for="permission in permissions"
                            :key="permission.key"
                            class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                        >
                            <p class="font-medium">{{ permission.label }}</p>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ permission.description }}
                            </p>
                            <p
                                class="mt-3 text-xs tracking-[0.2em] text-muted-foreground uppercase"
                            >
                                {{ permission.module }}
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section
                    class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
                >
                    <h2 class="text-lg font-semibold tracking-tight">
                        Members with this role
                    </h2>
                    <div class="mt-4 space-y-3">
                        <article
                            v-for="member in members"
                            :key="member.id"
                            class="rounded-xl border border-border/50 bg-muted/20 p-4"
                        >
                            <p class="font-medium">{{ member.name }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ member.email }}
                            </p>
                            <p
                                class="mt-2 text-xs tracking-[0.18em] text-muted-foreground uppercase"
                            >
                                {{ member.department }}
                            </p>
                        </article>
                    </div>
                </section>

                <section
                    class="rounded-2xl border border-amber-500/30 bg-amber-50/70 p-6 shadow-xs dark:bg-amber-950/20"
                >
                    <h2 class="text-lg font-semibold tracking-tight">
                        Review note
                    </h2>
                    <p class="mt-2 text-sm text-muted-foreground">
                        This role can mutate bookings and session capacity. Pair
                        with audit log review before widening access.
                    </p>
                </section>
            </aside>
        </div>
    </RbacPageShell>
</template>
