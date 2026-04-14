<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import PermissionForm from '@/pages/rbac/components/PermissionForm.vue';
import { rbacPaths } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import PermissionController from '@/actions/App/Http/Controllers/PermissionController';

const props = defineProps<{
    modules: Array<{
        id: number;
        name: string;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: '權限', href: rbacPaths.permissions },
    { title: '建立', href: `${rbacPaths.permissions}/create` },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="建立權限"
        description="定義新的權限代碼，並補上用途說明。"
    >
        <PermissionForm
            mode="create"
            :modules="props.modules"
            :action="PermissionController.store()"
        />
    </RbacPageShell>
</template>
