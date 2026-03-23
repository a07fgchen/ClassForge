<script setup lang="ts">
type Props = {
    label: string;
    value: string | number;
    hint: string;
};

defineProps<Props>();
</script>

<template>
    <article
        class="rounded-2xl border border-border/60 bg-background p-5 shadow-xs"
    >
        <p class="text-sm font-medium text-muted-foreground">{{ label }}</p>
        <p class="mt-3 text-3xl font-semibold tracking-tight">{{ value }}</p>
        <p class="mt-2 text-sm text-muted-foreground">{{ hint }}</p>
    </article>
</template>
