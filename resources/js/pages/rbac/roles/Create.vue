<script setup lang="ts">
import RbacPageShell from '@/pages/rbac/components/RbacPageShell.vue';
import RoleForm from '@/pages/rbac/components/RoleForm.vue';
import type { BreadcrumbItem } from '@/types';
import rbac from '@/routes/rbac';
import { store } from '@/actions/App/Http/Controllers/RoleController';
import { Permission } from '../types';


const props = withDefaults(defineProps<{
    permissions: Record<string, Permission[]>;
}>(), {
    permissions: () => ({}) // 提供預設值
});;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'RBAC', href: rbac.index() },
    { title: 'Roles', href: rbac.roles.index() },
    { title: 'Create', href: rbac.roles.create() },
];

</script>

<template>
    <RbacPageShell :breadcrumbs="breadcrumbs" title="Create role"
        description="Compose a new role definition with scoped access and a permission matrix ready for future backend submission.">
        <RoleForm mode="create" :submit="store.form()" :permissions="props.permissions" />
    </RbacPageShell>
</template>
