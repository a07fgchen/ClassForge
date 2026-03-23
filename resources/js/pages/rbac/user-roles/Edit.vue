<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import UserRoleAssignmentPanel from '@/pages/rbac/components/UserRoleAssignmentPanel.vue';
import { rbacPaths } from '@/pages/rbac/fixtures';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbacPaths.overview },
    { title: 'User roles', href: rbacPaths.userRoles },
    { title: 'Edit', href: `${rbacPaths.userRoles}/edit` },
];
</script>

<template>
    <RbacPageShell
        :breadcrumbs="breadcrumbs"
        title="Edit user assignments"
        description="Assign or revoke roles for a single user while keeping notification behavior explicit in the UI."
    >
        <UserRoleAssignmentPanel />
    </RbacPageShell>
</template>
