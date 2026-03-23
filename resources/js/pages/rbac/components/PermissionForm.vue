<script setup lang="ts">
import { reactive } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { roleNameOptions } from '@/pages/rbac/fixtures';
import type { PermissionFormState } from '@/pages/rbac/types';

type Props = {
    mode: 'create' | 'edit';
    initial: PermissionFormState;
};

const props = defineProps<Props>();

const form = reactive<PermissionFormState>({
    ...props.initial,
    assignedRoles: [...props.initial.assignedRoles],
});
</script>

<template>
    <form class="space-y-8" @submit.prevent>
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)]">
            <div
                class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="permission-name">Permission key</Label>
                        <Input
                            id="permission-name"
                            v-model="form.name"
                            placeholder="e.g. enrollments.override"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="permission-module">Module</Label>
                        <Input
                            id="permission-module"
                            v-model="form.module"
                            placeholder="Identity"
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="permission-description">Description</Label>
                    <textarea
                        id="permission-description"
                        v-model="form.description"
                        class="min-h-32 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        placeholder="Explain exactly what actions this permission unlocks."
                    />
                </div>

                <div class="space-y-3">
                    <Label>Risk level</Label>
                    <div class="grid gap-4 md:grid-cols-3">
                        <label
                            class="rounded-xl border border-border/60 bg-muted/30 p-4"
                        >
                            <div class="flex items-start gap-3">
                                <input
                                    v-model="form.risk"
                                    type="radio"
                                    value="low"
                                    class="mt-1"
                                />
                                <div>
                                    <p class="font-medium">Low</p>
                                    <p
                                        class="mt-1 text-sm text-muted-foreground"
                                    >
                                        Read-only or non-destructive actions.
                                    </p>
                                </div>
                            </div>
                        </label>

                        <label
                            class="rounded-xl border border-border/60 bg-muted/30 p-4"
                        >
                            <div class="flex items-start gap-3">
                                <input
                                    v-model="form.risk"
                                    type="radio"
                                    value="medium"
                                    class="mt-1"
                                />
                                <div>
                                    <p class="font-medium">Medium</p>
                                    <p
                                        class="mt-1 text-sm text-muted-foreground"
                                    >
                                        Can change operational data or customer
                                        bookings.
                                    </p>
                                </div>
                            </div>
                        </label>

                        <label
                            class="rounded-xl border border-border/60 bg-muted/30 p-4"
                        >
                            <div class="flex items-start gap-3">
                                <input
                                    v-model="form.risk"
                                    type="radio"
                                    value="high"
                                    class="mt-1"
                                />
                                <div>
                                    <p class="font-medium">High</p>
                                    <p
                                        class="mt-1 text-sm text-muted-foreground"
                                    >
                                        Can impact finance, identity, or
                                        compliance data.
                                    </p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <aside
                class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <h2 class="text-lg font-semibold tracking-tight">
                    Assignment snapshot
                </h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    Select which roles should inherit this permission by
                    default.
                </p>

                <div class="mt-4 space-y-3">
                    <label
                        v-for="roleName in roleNameOptions"
                        :key="roleName"
                        class="flex items-center gap-3 rounded-xl border border-border/50 bg-muted/20 p-4"
                    >
                        <input
                            v-model="form.assignedRoles"
                            type="checkbox"
                            :value="roleName"
                            class="size-4"
                        />
                        <span class="text-sm font-medium">{{ roleName }}</span>
                    </label>
                </div>
            </aside>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-3">
            <Button type="button" variant="outline">Preview impact</Button>
            <Button type="submit">
                {{
                    mode === 'create'
                        ? 'Create permission'
                        : 'Update permission'
                }}
            </Button>
        </div>
    </form>
</template>
