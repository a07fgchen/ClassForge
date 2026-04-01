<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import {
    permissionOptions,
    rbacPaths,
    roleRecords,
    userRoleRecords,
} from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import RoleController from '@/actions/App/Http/Controllers/RoleController';

type Role = {
    id: number;
    display_name: string;
    description: string;
    scope: number;
    is_protected: boolean;
    updated_at: string;
    permissions: {
        id: number;
        name: string;
        description: string;
        module: string;
    }[];
    users: {
        id: number;
        name: string;
        email: string;
    }[];
};
const props = defineProps<{
    role: Role;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'Roles', href: rbacPaths.roles },
    { title: props.role.display_name, href: RoleController.show(props.role.id) },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        :title="role.display_name"
        :description="role.description"
    >
        <div
            class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]"
        >
            <section
                class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >

                <div class="grid gap-4 md:grid-cols-2">
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">
                            已分配成員
                        </p>
                        <p class="mt-2 text-2xl font-semibold">
                            {{ role.users.length }}
                        </p>
                    </article>
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">Updated at</p>
                        <p class="mt-2 text-2xl font-semibold">
                            {{ role.updated_at }}
                        </p>
                    </article>
                </div>

                <div class="space-y-4">
                    <h2 class="text-lg font-semibold tracking-tight">
                        權限範圍
                    </h2>
                    <div class="grid gap-4 lg:grid-cols-2">
                        <article
                            v-for="permission in role.permissions"
                            :key="permission.id"
                            class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                        >
                            <p class="font-medium">{{ permission.name }}</p>
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
                        擁有此角色的成員
                    </h2>
                    <div class="mt-4 space-y-3">
                        <article
                            v-for="user in role.users"
                            :key="user.id"
                            class="rounded-xl border border-border/50 bg-muted/20 p-4"
                        >
                            <p class="font-medium">{{ user.name }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ user.email }}
                            </p>
                        </article>
                    </div>
                </section>
            </aside>
        </div>
    </RbacPageShell>
</template>
