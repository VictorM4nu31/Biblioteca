<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    items?: any;
    filters?: any;
}

const props = withDefaults(defineProps<Props>(), {
    items: () => ({ data: [] }),
    filters: () => ({}),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Items'),
        href: '/library/items',
    },
];
</script>

<template>
    <Head :title="$t('Items')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Items') }}</h1>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="item in items.data"
                    :key="item.id"
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <h3 class="font-semibold">{{ item.title }}</h3>
                    <p v-if="item.author" class="text-sm text-muted-foreground">
                        {{ item.author }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        {{ item.type }}
                    </p>
                </div>
            </div>

            <div v-if="items.data.length === 0" class="text-center text-muted-foreground">
                {{ $t('No items found') }}
            </div>
        </div>
    </AppLayout>
</template>
