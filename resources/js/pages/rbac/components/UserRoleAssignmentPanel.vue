<script setup lang="ts">
import { reactive } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { roleNameOptions, userRoleRecords } from '@/pages/rbac/fixtures';

const selectedUser = userRoleRecords[1];

const form = reactive({
    name: selectedUser.name,
    email: selectedUser.email,
    department: selectedUser.department,
    roles: [...selectedUser.roles],
    sendNotification: true,
});
</script>

<template>
    <form class="space-y-8" @submit.prevent>
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
            <div
                class="space-y-6 rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="user-name">User name</Label>
                        <Input id="user-name" v-model="form.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="user-email">Email</Label>
                        <Input id="user-email" v-model="form.email" />
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="user-department">Department</Label>
                    <Input id="user-department" v-model="form.department" />
                </div>

                <label
                    class="flex items-center gap-3 rounded-xl border border-border/60 bg-muted/30 p-4"
                >
                    <input
                        v-model="form.sendNotification"
                        type="checkbox"
                        class="size-4"
                    />
                    <div>
                        <p class="font-medium">Send assignment notification</p>
                        <p class="text-sm text-muted-foreground">
                            Notify the user so they can review the newly granted
                            access.
                        </p>
                    </div>
                </label>
            </div>

            <aside
                class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs"
            >
                <h2 class="text-lg font-semibold tracking-tight">
                    Assigned roles
                </h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    Use multi-role assignments carefully. Overlaps should still
                    be auditable.
                </p>

                <div class="mt-4 space-y-3">
                    <label
                        v-for="roleName in roleNameOptions"
                        :key="roleName"
                        class="flex items-center gap-3 rounded-xl border border-border/50 bg-muted/20 p-4"
                    >
                        <input
                            v-model="form.roles"
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
            <Button type="button" variant="outline">Review diff</Button>
            <Button type="submit">Update assignments</Button>
        </div>
    </form>
</template>
