<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { RoleFormState } from '@/pages/rbac/types';
import { Form, useForm } from '@inertiajs/vue3';
import { RouteFormDefinition } from '@/wayfinder';
import RoleController from '@/actions/App/Http/Controllers/RoleController';
import { ref } from 'vue';

interface Permission {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    module_id: number;
    module: {
        id: number;
        name: string;
    };
}

interface ScopeOption {
    value: 1 | 2;
    label: string;
    description: string;
}

type Props = {
    mode: 'create' | 'edit';
    initial: RoleFormState;
    action: RouteFormDefinition<'post' | 'put' | 'patch' | 'delete'>;
    permissions: Record<string, Permission[]>;
};

const scopeOptions: ScopeOption[] = [
    {
        value: 1,
        label: 'Tenant scope',
        description: 'Best for merchant-level operational roles.',
    },
    {
        value: 2,
        label: 'Platform scope',
        description: 'Reserved for global operations and platform governance.',
    },
];
const props = defineProps<Props>();
const form = useForm<RoleFormState>({
    ...props.initial,
    permissions: [...props.initial.permissions],
});
</script>

<template>
    <Form class="space-y-8" :action="RoleController.store()" #default="{ errors }">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)]">
            <div class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                <div class="space-y-2">
                    <Label for="role-name">Role name</Label>
                    <Input id="role-name" name="display_name" placeholder="e.g. Instructor Operations" />
                    <div class="text-red-500">
                        <span v-if="errors['display_name']">
                            {{ errors['display_name'] }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="role-description">Description</Label>
                    <textarea id="role-description" name="description"
                        class="min-h-32 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        placeholder="Describe where this role is used and what level of trust it carries." />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label v-for="(option, index) in scopeOptions" :key="option.value"
                        class="rounded-xl border border-border/60 bg-muted/30 p-4">
                        <div class="flex items-start gap-3">
                            <input type="radio" name="scope" :value="option.value" :checked="index === 0" class="mt-1" />
                            <div>
                                <p class="font-medium">{{ option.label }}</p>
                                <p class="mt-1 text-sm text-muted-foreground">
                                    {{ option.description }}
                                </p>
                            </div>
                        </div>
                    </label>
                </div>

                <label class="flex items-center gap-3 rounded-xl border border-border/60 bg-muted/30 p-4">
                    <input type="checkbox" name="is_protected" value="1" defaultChecked class="size-4" />
                    <div>
                        <p class="font-medium">System protected role</p>
                        <p class="text-sm text-muted-foreground">
                            System roles are visible but should require extra
                            approval before deletion.
                        </p>
                    </div>
                </label>
            </div>

            <aside class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                <h2 class="text-lg font-semibold tracking-tight">Review</h2>
                <dl class="mt-4 space-y-4 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-muted-foreground">Mode</dt>
                        <dd class="font-medium">
                            {{
                                mode === 'create' ? 'Create role' : 'Edit role'
                            }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-muted-foreground">Scope</dt>
                        <dd class="font-medium capitalize">{{ form.scope }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-muted-foreground">Permissions</dt>
                        <dd class="font-medium">
                            {{ form.permissions.length }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-muted-foreground">Protected</dt>
                        <dd class="font-medium">
                            {{ form.isSystem ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-6 rounded-xl bg-muted/40 p-4 text-sm text-muted-foreground">
                    This form is currently UI-only. You can wire it to Inertia
                    actions later without changing the layout.
                </div>
            </aside>
        </div>

        <section class="space-y-5 rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold tracking-tight">
                    Permission matrix
                </h2>
                <p class="text-sm text-muted-foreground">
                    Group permissions by business module so reviewers can audit
                    access faster.
                </p>
            </div>

            <div class="grid gap-5 xl:grid-cols-2">
                <article v-for="(permissions, module) in props.permissions" :key="module"
                    class="rounded-2xl border border-border/60 bg-muted/20 p-5">
                    <h3 class="text-base font-semibold">{{ module }}</h3>
                    <div class="mt-4 space-y-3">
                        <label v-for="permission in permissions" :key="permission.id"
                            class="flex gap-3 rounded-xl border border-border/50 bg-background p-4">
                            <input type="checkbox" name="permissions[]" :value="permission.id" class="mt-1 size-4" />
                            <div class="space-y-1">
                                <p class="font-medium">{{ permission.name }}</p>
                                <p class="text-sm text-muted-foreground">
                                    {{ permission.description }}
                                </p>
                                <p class="text-xs tracking-[0.2em] text-muted-foreground uppercase">
                                    {{ permission.slug }}
                                </p>
                            </div>
                        </label>
                    </div>
                </article>
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-end gap-3">
            <Button type="button" variant="outline">Save as draft</Button>
            <Button type="submit">{{
                mode === 'create' ? 'Create role' : 'Update role'
                }}</Button>
        </div>
    </Form>
</template>
