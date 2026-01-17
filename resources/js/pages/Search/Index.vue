<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    items?: any;
    categories?: any[];
    tags?: any[];
    filters?: any;
}

const props = withDefaults(defineProps<Props>(), {
    items: () => ({ data: [] }),
    categories: () => [],
    tags: () => [],
    filters: () => ({}),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Search'),
        href: '/search',
    },
];
</script>

<template>
    <Head :title="$t('Search')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Search Results') }}</h1>

            <div v-if="filters.q" class="text-muted-foreground">
                {{ $t('Searching for') }}: <strong>{{ filters.q }}</strong>
            </div>

            <div class="grid gap-4">
                <div
                    v-for="item in items.data"
                    :key="item.id"
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <h3 class="font-semibold">{{ item.title }}</h3>
                    <p v-if="item.author" class="text-sm text-muted-foreground">
                        {{ $t('Author') }}: {{ item.author }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        {{ $t('Type') }}: {{ item.type }}
                    </p>
                </div>
            </div>

            <div v-if="items.data.length === 0" class="text-center text-muted-foreground">
                {{ $t('No results found') }}
            </div>
        </div>
    </AppLayout>
</template>
