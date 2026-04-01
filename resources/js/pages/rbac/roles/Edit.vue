<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import RoleForm from '@/pages/rbac/components/RoleForm.vue';
import type { BreadcrumbItem } from '@/types';
import RoleController, { update } from '@/actions/App/Http/Controllers/RoleController';
import rbac from '@/routes/rbac';
import { Permission } from '../types';

type Role = {
    id: number;
    display_name: string;
    description: string;
    scope: number;
    is_protected: boolean;
    permissions: {
        id: number;
        name: string;
        description: string;
        module: string;
    }[];
};

const props = defineProps<{
    role: Role;
    permissions: Record<string, Permission[]>;
}>();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbac.index() },
    { title: 'Roles', href: RoleController.index() },
    { title: 'Edit', href: RoleController.edit(props.role.id) },
];

</script>

<template>
    <RbacPageShell :breadcrumbs="breadcrumbs" title="Edit role"
        description="Adjust permission coverage while keeping scope and audit expectations visible to reviewers.">
        <RoleForm mode="edit" :submit="update.form(role.id)" :permissions="props.permissions" />
    </RbacPageShell>
</template>
