<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import PermissionForm from '@/pages/rbac/components/PermissionForm.vue';
import type { BreadcrumbItem } from '@/types';
import PermissionController from '@/actions/App/Http/Controllers/PermissionController';
import rbac from '@/routes/rbac';

const props = defineProps<{
    permission: {
        id: number;
        name: string;
        description: string | null;
        slug: string;
        module_id: number | null;
    };
    modules: Array<{
        id: number;
        name: string;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbac.index() },
    { title: '權限', href: PermissionController.index() },
    { title: '編輯', href: PermissionController.edit(props.permission.id) },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="編輯權限"
        description="調整權限定義與描述，確保權限設定清晰且一致。"
    >
        <PermissionForm
            mode="edit"
            :modules="modules"
            :action="PermissionController.update(permission.id)"
        />
    </RbacPageShell>
</template>
