<script setup lang="ts">
import { reactive } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { roleNameOptions } from '@/pages/rbac/fixtures';
import { Form } from '@inertiajs/vue3';
import PermissionController from '@/actions/App/Http/Controllers/PermissionController';

type Props = {
    mode: 'create' | 'edit';
    initial: object;
};

const props = defineProps<Props>();

</script>

<template>
    <Form class="space-y-8" :action="PermissionController.store()">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)]">
            <div class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="permission-name">權限名稱</Label>
                        <Input id="permission-name" name="name" placeholder="" />
                    </div>

                    <div class="space-y-2">
                        <Label for="permission-slug">權限唯一標示</Label>
                        <Input id="permission-slug" name="slug" placeholder="e.g. enrollments.override" />
                    </div>

                    <div class="space-y-2">
                        <Label for="permission-module">模組名稱</Label>
                        <Input id="permission-module" name="module" placeholder="Identity" />
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="permission-description">描述</Label>
                    <textarea id="permission-description" name="description"
                        class="min-h-32 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        placeholder="權限描述" />
                </div>
            </div>

            <aside class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                <h2 class="text-lg font-semibold tracking-tight">
                    Assignment snapshot
                </h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    Select which roles should inherit this permission by
                    default.
                </p>

                <div class="mt-4 space-y-3">
                    <label v-for="roleName in roleNameOptions" :key="roleName"
                        class="flex items-center gap-3 rounded-xl border border-border/50 bg-muted/20 p-4">
                        <input name="role" type="checkbox" :value="roleName" class="size-4" />
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
    </Form>
</template>
