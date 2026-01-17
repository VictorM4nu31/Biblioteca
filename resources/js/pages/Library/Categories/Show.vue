<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    category?: any;
}

const props = withDefaults(defineProps<Props>(), {
    category: () => ({}),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.category?.name || trans('Category'),
        href: `/library/categories/${props.category?.id}`,
    },
];
</script>

<template>
    <Head :title="category.name || $t('Category')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ category.name }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p v-if="category.description" class="text-muted-foreground">
                    {{ category.description }}
                </p>
                <p v-if="category.items" class="mt-4 text-sm text-muted-foreground">
                    {{ $t('Items in this category') }}: {{ category.items?.length || 0 }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
