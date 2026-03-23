<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    title: string;
    description: string;
    breadcrumbs: BreadcrumbItem[];
};

defineProps<Props>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="title" />

        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
            <section
                class="overflow-hidden rounded-2xl border border-sidebar-border/70 bg-card shadow-xs dark:border-sidebar-border"
            >
                <div
                    class="flex flex-col gap-4 border-b border-border/60 bg-linear-to-r from-slate-50 via-white to-cyan-50/60 px-6 py-6 md:flex-row md:items-end md:justify-between dark:from-slate-950 dark:via-card dark:to-cyan-950/20"
                >
                    <div class="space-y-2">
                        <p
                            class="text-xs font-semibold tracking-[0.24em] text-cyan-700 uppercase dark:text-cyan-300"
                        >
                            RBAC Workspace
                        </p>
                        <div class="space-y-1">
                            <h1
                                class="text-2xl font-semibold tracking-tight md:text-3xl"
                            >
                                {{ title }}
                            </h1>
                            <p
                                class="max-w-3xl text-sm text-muted-foreground md:text-base"
                            >
                                {{ description }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <slot name="actions" />
                    </div>
                </div>

                <div class="p-6">
                    <slot />
                </div>
            </section>
        </div>
    </AppLayout>
</template>
