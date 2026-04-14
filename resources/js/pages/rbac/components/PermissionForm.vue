<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RouteDefinition } from '@/wayfinder';
import { useForm } from '@inertiajs/vue3';

type Props = {
    mode: 'create' | 'edit';
    modules: Array<{
        id: number;
        name: string;
    }>;
    action: RouteDefinition<'post' | 'put' | 'patch'>;
};

const props = defineProps<Props>();

const form = useForm({
    name: '',
    slug: '',
    description: '',
    module_id: '',
});

</script>

<template>
    <form class="space-y-8" @submit.prevent="form.submit(action)" >
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)]">
            <div class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="permission-name">權限名稱</Label>
                        <Input id="permission-name" v-model="form.name" placeholder="例如：退款審核" />
                        <div v-if="form.errors.name" class="text-sm text-destructive">
                            <span> {{ form.errors.name }} </span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="permission-slug">權限唯一識別碼</Label>
                        <Input id="permission-slug" v-model="form.slug" placeholder="例如：enrollments.override" />
                        <div v-if="form.errors.slug" class="text-sm text-destructive">
                            <span> {{ form.errors.slug }} </span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="permission-module">模組名稱</Label>
                        <select id="permission-module" v-model="form.module_id"
                            class="h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50">
                            <option disabled value="">請選擇模組</option>
                            <option v-for="module in props.modules" :key="module.id" :value="module.id">
                                {{ module.name }}
                            </option>

                        </select>
                        <div v-if="form.errors.module_id" class="text-sm text-destructive">
                            <span> {{ form.errors.module_id }} </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="permission-description">描述</Label>
                    <textarea id="permission-description" v-model="form.description"
                        class="min-h-32 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        placeholder="權限描述" />
                </div>
            </div>

            <!-- <aside class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                <h2 class="text-lg font-semibold tracking-tight">
                    指派摘要
                </h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    選擇哪些角色在預設情況下應繼承這個權限。
                </p>

                <div class="mt-4 space-y-3">
                    <label v-for="roleName in roleNameOptions" :key="roleName"
                        class="flex items-center gap-3 rounded-xl border border-border/50 bg-muted/20 p-4">
                        <input name="role" type="checkbox" :value="roleName" class="size-4" />
                        <span class="text-sm font-medium">{{ roleName }}</span>
                    </label>
                </div>
            </aside> -->
        </div>

        <div class="flex flex-wrap items-center justify-end gap-3">
            <Button type="submit">
                {{
                    mode === 'create'
                        ? '建立權限'
                        : '更新權限'
                }}
            </Button>
        </div>
    </form>
</template>
