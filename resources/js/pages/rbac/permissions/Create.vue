<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import PermissionForm from '@/pages/rbac/components/PermissionForm.vue';
import { initialPermissionForm, rbacPaths } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import PermissionController from '@/actions/App/Http/Controllers/PermissionController';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'Permissions', href: rbacPaths.permissions },
    { title: 'Create', href: `${rbacPaths.permissions}/create` },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="Create permission"
        description="Define a new permission key, classify its risk, and preview which roles should inherit it."
    >
        <PermissionForm mode="create" :initial="PermissionController.store.form()" />
    </RbacPageShell>
</template>
