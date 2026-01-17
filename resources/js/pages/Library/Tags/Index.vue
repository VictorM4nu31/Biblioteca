<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    tags?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    tags: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Tags'),
        href: '/library/tags',
    },
];
</script>

<template>
    <Head :title="$t('Tags')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Tags') }}</h1>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="tag in tags"
                    :key="tag.id"
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <h3 class="font-semibold">{{ tag.name }}</h3>
                    <p class="text-sm text-muted-foreground">
                        {{ tag.items_count }} {{ $t('items') }}
                    </p>
                </div>
            </div>

            <div v-if="tags.length === 0" class="text-center text-muted-foreground">
                {{ $t('No tags found') }}
            </div>
        </div>
    </AppLayout>
</template>
