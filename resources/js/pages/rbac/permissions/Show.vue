<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import {
    permissionRecords,
    rbacPaths,
    roleRecords,
} from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';

const permission = permissionRecords[3];
const relatedRoles = roleRecords.filter((role) =>
    role.permissions.includes(permission.name),
);

const riskVariant = {
    low: 'outline',
    medium: 'secondary',
    high: 'destructive',
} as const;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'Permissions', href: rbacPaths.permissions },
    {
        title: permission.name,
        href: `${rbacPaths.permissions}/${permission.id}`,
    },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        :title="permission.name"
        :description="permission.description"
    >
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
            <section
                class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <div class="flex flex-wrap items-center gap-2">
                    <Badge :variant="riskVariant[permission.risk]">{{
                        permission.risk
                    }}</Badge>
                    <Badge variant="outline">{{ permission.module }}</Badge>
                    <Badge variant="outline"
                        >{{ relatedRoles.length }} roles inherit this</Badge
                    >
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">
                            Registry key
                        </p>
                        <p class="mt-2 font-semibold">{{ permission.name }}</p>
                    </article>
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">Risk</p>
                        <p class="mt-2 font-semibold capitalize">
                            {{ permission.risk }}
                        </p>
                    </article>
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">Updated</p>
                        <p class="mt-2 font-semibold">
                            {{ permission.updatedAt }}
                        </p>
                    </article>
                </div>

                <section
                    class="rounded-2xl border border-border/50 bg-muted/20 p-5"
                >
                    <h2 class="text-lg font-semibold tracking-tight">
                        Operational impact
                    </h2>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Refund actions affect customer trust, finance
                        reconciliation, and downstream reporting. Approval flow
                        is recommended.
                    </p>
                </section>
            </section>

            <aside
                class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <h2 class="text-lg font-semibold tracking-tight">
                    Inherited by roles
                </h2>
                <div class="mt-4 space-y-3">
                    <article
                        v-for="role in relatedRoles"
                        :key="role.id"
                        class="rounded-xl border border-border/50 bg-muted/20 p-4"
                    >
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-medium">{{ role.name }}</p>
                            <Badge variant="outline">{{ role.scope }}</Badge>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ role.description }}
                        </p>
                    </article>
                </div>
            </aside>
        </div>
    </RbacPageShell>
</template>
