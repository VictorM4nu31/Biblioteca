<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    categories?: any[];
    tags?: any[];
    languages?: any[];
    years?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    categories: () => [],
    tags: () => [],
    languages: () => [],
    years: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Advanced Search'),
        href: '/search/advanced',
    },
];
</script>

<template>
    <Head :title="$t('Advanced Search')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Advanced Search') }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p class="text-muted-foreground">{{ $t('Search form will go here') }}</p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Categories') }}: {{ categories.length }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Tags') }}: {{ tags.length }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Languages') }}: {{ languages.length }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
