<script setup lang="ts">
import PermissionController from '@/actions/App/Http/Controllers/PermissionController';
import { Badge } from '@/components/ui/badge';
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import { roleRecords } from '@/pages/rbac/fixtures';
import rbac from '@/routes/rbac';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    permission: {
        id: number;
        name: string;
        description: string;
        slug: string;
        module: {
            id: number;
            name: string;
        };
        updated_at: string;
    };
}>();

const relatedRoles = roleRecords.filter((role) =>
    role.permissions.includes(props.permission.name),
);

const scopeLabels = {
    platform: '平台',
    tenant: '租戶',
} as const;

const moduleLabels: Record<string, string> = {
    Courses: '課程',
    Bookings: '預約',
    Commerce: '商務',
    Identity: '身分',
    Governance: '治理',
    Reports: '報表',
};

const formattedUpdatedAt = new Date(props.permission.updated_at).toLocaleString(
    'zh-TW',
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbac.index() },
    { title: '權限', href: PermissionController.index() },
    {
        title: props.permission.name,
        href: PermissionController.show(props.permission.id),
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
                    <Badge variant="outline">
                        {{ moduleLabels[permission.module.name] ?? permission.module.name }}
                    </Badge>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">
                            權限代碼
                        </p>
                        <p class="mt-2 font-semibold">{{ permission.name }}</p>
                    </article>
                    <article
                        class="rounded-2xl border border-border/50 bg-muted/20 p-4"
                    >
                        <p class="text-sm text-muted-foreground">最後更新</p>
                        <p class="mt-2 font-semibold">
                            {{ formattedUpdatedAt }}
                        </p>
                    </article>
                </div>

                <section
                    class="rounded-2xl border border-border/50 bg-muted/20 p-5"
                >
                    <h2 class="text-lg font-semibold tracking-tight">
                        作業影響
                    </h2>
                    <p class="mt-2 text-sm text-muted-foreground">
                        此權限會影響日常作業流程、稽核追蹤與後續管理作業，建議搭配審核流程與最小權限原則使用。
                    </p>
                </section>
            </section>

            <aside
                class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <h2 class="text-lg font-semibold tracking-tight">
                    繼承此權限的角色
                </h2>
                <div class="mt-4 space-y-3">
                    <article
                        v-for="role in relatedRoles"
                        :key="role.id"
                        class="rounded-xl border border-border/50 bg-muted/20 p-4"
                    >
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-medium">{{ role.name }}</p>
                            <Badge variant="outline">
                                {{ scopeLabels[role.scope] }}
                            </Badge>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ role.description }}
                        </p>
                    </article>
                    <p
                        v-if="relatedRoles.length === 0"
                        class="rounded-xl border border-dashed border-border/50 bg-muted/10 p-4 text-sm text-muted-foreground"
                    >
                        目前沒有角色繼承此權限。
                    </p>
                </div>
            </aside>
        </div>
    </RbacPageShell>
</template>
