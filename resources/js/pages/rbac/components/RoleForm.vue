<script setup lang="ts">
import { computed, reactive } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { permissionOptions } from '@/pages/rbac/fixtures';
import type { RoleFormState } from '@/pages/rbac/types';
import { Form } from '@inertiajs/vue3';
import { store } from '@/routes/rbac/roles';
import { RouteFormDefinition } from '@/wayfinder';

type Props = {
    mode: 'create' | 'edit';
    initial: RoleFormState;
    action: RouteFormDefinition<'post' | 'put' | 'patch' | 'delete'>;
};

const props = defineProps<Props>();

const form = reactive<RoleFormState>({
    ...props.initial,
    permissions: [...props.initial.permissions],
});

const groupedPermissions = computed(() => {
    return permissionOptions.reduce<Record<string, typeof permissionOptions>>(
        (groups, option) => {
            if (!groups[option.module]) {
                groups[option.module] = [];
            }

            groups[option.module].push(option);

            return groups;
        },
        {},
    );
});
</script>

<template>
    <Form class="space-y-8" v-bind="props.action">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)]">
            <div
                class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <div class="space-y-2">
                    <Label for="role-name">Role name</Label>
                    <Input
                        id="role-name"
                        v-model="form.name"
                        placeholder="e.g. Instructor Operations"
                    />
                </div>

                <div class="space-y-2">
                    <Label for="role-description">Description</Label>
                    <textarea
                        id="role-description"
                        v-model="form.description"
                        class="min-h-32 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        placeholder="Describe where this role is used and what level of trust it carries."
                    />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label
                        class="rounded-xl border border-border/60 bg-muted/30 p-4"
                    >
                        <div class="flex items-start gap-3">
                            <input
                                v-model="form.scope"
                                type="radio"
                                value="tenant"
                                class="mt-1"
                            />
                            <div>
                                <p class="font-medium">Tenant scope</p>
                                <p class="mt-1 text-sm text-muted-foreground">
                                    Best for merchant-level operational roles.
                                </p>
                            </div>
                        </div>
                    </label>

                    <label
                        class="rounded-xl border border-border/60 bg-muted/30 p-4"
                    >
                        <div class="flex items-start gap-3">
                            <input
                                v-model="form.scope"
                                type="radio"
                                value="platform"
                                class="mt-1"
                            />
                            <div>
                                <p class="font-medium">Platform scope</p>
                                <p class="mt-1 text-sm text-muted-foreground">
                                    Reserved for global operations and platform
                                    governance.
                                </p>
                            </div>
                        </div>
                    </label>
                </div>

                <label
                    class="flex items-center gap-3 rounded-xl border border-border/60 bg-muted/30 p-4"
                >
                    <input
                        v-model="form.isSystem"
                        type="checkbox"
                        class="size-4"
                    />
                    <div>
                        <p class="font-medium">System protected role</p>
                        <p class="text-sm text-muted-foreground">
                            System roles are visible but should require extra
                            approval before deletion.
                        </p>
                    </div>
                </label>
            </div>

            <aside
                class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
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

                <div
                    class="mt-6 rounded-xl bg-muted/40 p-4 text-sm text-muted-foreground"
                >
                    This form is currently UI-only. You can wire it to Inertia
                    actions later without changing the layout.
                </div>
            </aside>
        </div>

        <section
            class="space-y-5 rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
        >
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
                <article
                    v-for="(options, module) in groupedPermissions"
                    :key="module"
                    class="rounded-2xl border border-border/60 bg-muted/20 p-5"
                >
                    <h3 class="text-base font-semibold">{{ module }}</h3>
                    <div class="mt-4 space-y-3">
                        <label
                            v-for="option in options"
                            :key="option.key"
                            class="flex gap-3 rounded-xl border border-border/50 bg-background p-4"
                        >
                            <input
                                v-model="form.permissions"
                                type="checkbox"
                                :value="option.key"
                                class="mt-1 size-4"
                            />
                            <div class="space-y-1">
                                <p class="font-medium">{{ option.label }}</p>
                                <p class="text-sm text-muted-foreground">
                                    {{ option.description }}
                                </p>
                                <p
                                    class="text-xs tracking-[0.2em] text-muted-foreground uppercase"
                                >
                                    {{ option.key }}
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
