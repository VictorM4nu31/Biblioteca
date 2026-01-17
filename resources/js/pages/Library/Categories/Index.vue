<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    categories?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    categories: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Categories'),
        href: '/library/categories',
    },
];
</script>

<template>
    <Head :title="$t('Categories')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Categories') }}</h1>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <h3 class="font-semibold">{{ category.name }}</h3>
                    <p class="text-sm text-muted-foreground">
                        {{ category.items_count }} {{ $t('items') }}
                    </p>
                </div>
            </div>

            <div v-if="categories.length === 0" class="text-center text-muted-foreground">
                {{ $t('No categories found') }}
            </div>
        </div>
    </AppLayout>
</template>
