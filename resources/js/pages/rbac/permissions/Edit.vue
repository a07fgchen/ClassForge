<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import PermissionForm from '@/pages/rbac/components/PermissionForm.vue';
import type { BreadcrumbItem } from '@/types';
import RoleController from '@/actions/App/Http/Controllers/RoleController';
import PermissionController from '@/actions/App/Http/Controllers/PermissionController';

const permissionId = 4;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: RoleController.index() },
    { title: 'Permissions', href: PermissionController.index() },
    { title: 'Edit', href: `${rbacPaths.permissions}/${permissionId}/edit` },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="Edit permission"
        description="Refine permission semantics without losing clarity about which roles inherit the capability."
    >
        <PermissionForm mode="edit" :initial="editingPermissionForm" />
    </RbacPageShell>
</template>
