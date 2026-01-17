<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    collections?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    collections: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('My Collections'),
        href: '/user/collections/my',
    },
];
</script>

<template>
    <Head :title="$t('My Collections')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('My Collections') }}</h1>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="collection in collections"
                    :key="collection.id"
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <h3 class="font-semibold">{{ collection.name }}</h3>
                    <p v-if="collection.description" class="text-sm text-muted-foreground">
                        {{ collection.description }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        {{ collection.items_count }} {{ $t('items') }}
                    </p>
                </div>
            </div>

            <div v-if="collections.length === 0" class="text-center text-muted-foreground">
                {{ $t('No collections found') }}
            </div>
        </div>
    </AppLayout>
</template>
