<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { RouteFormDefinition } from '@/wayfinder';
import { Permission } from '../types';

interface ScopeOption {
    value: 1 | 2;
    label: string;
    description: string;
}

type Props = {
    mode: 'create' | 'edit';
    submit: RouteFormDefinition;
    permissions: Record<string, Permission[]>;
};

const scopeOptions: ScopeOption[] = [
    {
        value: 1,
        label: '租戶區塊',
        description: '租戶角色只能被分配給同一租戶底下的使用者，適合大多數自定義角色的使用情境。',
    },
    {
        value: 2,
        label: '平台區塊',
        description: '平台角色可以被分配給任何租戶底下的使用者，適合需要跨租戶管理權限的系統角色，但請謹慎使用以免造成權限濫用。',
    },
];

const props = defineProps<Props>();

const form = useForm({
    display_name: '',
    description: '',
    scope: 1,
    is_protected: false,
    permissions: [] as number[],
});

const selectedPermissionCount = computed(() => form.permissions.length);

const selectedScopeOption = computed(() => {
    return scopeOptions.find((option) => option.value === form.scope) ?? scopeOptions[0];
});

const submitForm = (): void => {
    form.submit(props.submit.method, props.submit.action);
};

</script>

<template>
    <form class="space-y-8" @submit.prevent="submitForm">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)]">
            <div class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                <div class="space-y-2">
                    <Label for="role-name">角色名稱</Label>
                    <Input id="role-name" v-model="form.display_name" placeholder="e.g. Instructor Operations" />
                    <div class="text-red-500">
                        <span v-if="form.errors.display_name">
                            {{ form.errors.display_name }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="role-description">描述</Label>
                    <textarea id="role-description" v-model="form.description"
                        class="min-h-32 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        placeholder="Describe where this role is used and what level of trust it carries." />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label v-for="option in scopeOptions" :key="option.value"
                        class="rounded-xl border border-border/60 bg-muted/30 p-4"
                        :class="form.scope === option.value ? 'border-primary/60 bg-primary/5' : ''">
                        <div class="flex items-start gap-3">
                            <input v-model="form.scope" type="radio" name="scope" :value="option.value" class="mt-1" />
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
                    <input v-model="form.is_protected" type="checkbox" name="is_protected" class="size-4" />
                    <div>
                        <p class="font-medium">系統保護角色</p>
                        <p class="text-sm text-muted-foreground">
                            系統角色可以被看到，但是刪除時需要有額外的審核。
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
                        <dd class="font-medium">{{ selectedScopeOption.label }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-muted-foreground">Permissions</dt>
                        <dd class="font-medium">
                            {{ selectedPermissionCount }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-muted-foreground">Protected</dt>
                        <dd class="font-medium">
                            {{ form.is_protected ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-6 rounded-xl bg-muted/40 p-4 text-sm text-muted-foreground">
                    The form submits directly to the configured role endpoint and keeps the permission matrix in sync with the review summary.
                </div>
            </aside>
        </div>

        <section class="space-y-5 rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold tracking-tight">
                    權限矩陣
                </h2>
                <p class="text-sm text-muted-foreground">
                    系統會把權限依照業務模組（例如課程、使用者、帳務）分組
                </p>
            </div>

            <div class="grid gap-5 xl:grid-cols-2">
                <article v-for="(permissions, module) in props.permissions" :key="module"
                    class="rounded-2xl border border-border/60 bg-muted/20 p-5">
                    <h3 class="text-base font-semibold">{{ module }}</h3>
                    <div class="mt-4 space-y-3">
                        <label v-for="permission in permissions" :key="permission.id"
                            class="flex gap-3 rounded-xl border border-border/50 bg-background p-4">
                            <input v-model="form.permissions" type="checkbox" name="permissions[]" :value="permission.id"
                                class="mt-1 size-4" />
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

            <p v-if="form.errors.permissions" class="text-sm text-red-500">
                {{ form.errors.permissions }}
            </p>
        </section>

        <div class="flex flex-wrap items-center justify-end gap-3">
            <Button type="submit" :disabled="form.processing">{{
                mode === 'create' ? 'Create role' : 'Update role'
            }}</Button>
        </div>
    </form>
</template>
