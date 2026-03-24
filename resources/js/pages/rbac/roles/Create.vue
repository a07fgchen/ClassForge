<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import RoleForm from '@/pages/rbac/components/RoleForm.vue';
import { initialRoleForm, rbacPaths } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import rbac from '@/routes/rbac';
import { store } from '@/actions/App/Http/Controllers/RoleController';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbac.index() },
    { title: 'Roles', href: rbac.roles.index() },
    { title: 'Create', href: rbac.roles.create() },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="Create role"
        description="Compose a new role definition with scoped access and a permission matrix ready for future backend submission."
    >
        <RoleForm
            mode="create"
            :initial="initialRoleForm"
            :action="store.form()"
        />
    </RbacPageShell>
</template>
