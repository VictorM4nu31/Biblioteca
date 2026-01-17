<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    collection?: any;
    isOwner?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    collection: () => ({}),
    isOwner: false,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.collection?.name || trans('Collection'),
        href: `/user/collections/${props.collection?.id}`,
    },
];
</script>

<template>
    <Head :title="collection.name || $t('Collection')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ collection.name }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p v-if="collection.description" class="text-muted-foreground">
                    {{ collection.description }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Items') }}: {{ collection.items?.length || 0 }}
                </p>
                <p v-if="isOwner" class="text-sm text-muted-foreground">
                    {{ $t('You are the owner') }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
