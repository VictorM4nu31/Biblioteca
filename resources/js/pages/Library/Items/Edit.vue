<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    item?: any;
    categories?: any[];
    tags?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    item: () => ({}),
    categories: () => [],
    tags: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Edit Item'),
        href: `/library/items/${props.item?.id}/edit`,
    },
];
</script>

<template>
    <Head :title="$t('Edit Item')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Edit Item') }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p class="text-muted-foreground">{{ $t('Edit form will go here') }}</p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Editing') }}: {{ item.title }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Categories available') }}: {{ categories.length }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Tags available') }}: {{ tags.length }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
