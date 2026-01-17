<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    item?: any;
    averageRating?: number;
    totalRatings?: number;
}

const props = withDefaults(defineProps<Props>(), {
    item: () => ({}),
    averageRating: 0,
    totalRatings: 0,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.item?.title || trans('Item'),
        href: `/library/items/${props.item?.id}`,
    },
];
</script>

<template>
    <Head :title="item.title || $t('Item')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ item.title }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p v-if="item.author" class="text-muted-foreground">
                    <strong>{{ $t('Author') }}:</strong> {{ item.author }}
                </p>
                <p v-if="item.type" class="text-muted-foreground">
                    <strong>{{ $t('Type') }}:</strong> {{ item.type }}
                </p>
                <p v-if="averageRating" class="text-muted-foreground">
                    <strong>{{ $t('Rating') }}:</strong> {{ averageRating.toFixed(1) }}
                    ({{ totalRatings }} {{ $t('ratings') }})
                </p>
                <p v-if="item.description" class="mt-4">{{ item.description }}</p>
            </div>
        </div>
    </AppLayout>
</template>
