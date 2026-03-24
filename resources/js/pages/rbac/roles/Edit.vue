<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import RoleForm from '@/pages/rbac/components/RoleForm.vue';
import { editingRoleForm, rbacPaths } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';
import { update } from '@/actions/App/Http/Controllers/RoleController';

const roleId = 3;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'Roles', href: rbacPaths.roles },
    { title: 'Edit', href: `${rbacPaths.roles}/${roleId}/edit` },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="Edit role"
        description="Adjust permission coverage while keeping scope and audit expectations visible to reviewers."
    >
        <RoleForm
            mode="edit"
            :initial="editingRoleForm"
            :action="update.form(roleId)"
        />
    </RbacPageShell>
</template>
