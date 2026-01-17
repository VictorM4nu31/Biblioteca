<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    ratings?: any;
}

const props = withDefaults(defineProps<Props>(), {
    ratings: () => ({ data: [] }),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('My Ratings'),
        href: '/user/ratings',
    },
];
</script>

<template>
    <Head :title="$t('My Ratings')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('My Ratings') }}</h1>

            <div class="grid gap-4">
                <div
                    v-for="rating in ratings.data"
                    :key="rating.id"
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <h3 class="font-semibold">{{ rating.item?.title }}</h3>
                    <p class="text-sm text-muted-foreground">
                        {{ $t('Rating') }}: {{ rating.rating }}/5
                    </p>
                    <p v-if="rating.review" class="mt-2 text-sm">
                        {{ rating.review }}
                    </p>
                </div>
            </div>

            <div v-if="ratings.data.length === 0" class="text-center text-muted-foreground">
                {{ $t('No ratings found') }}
            </div>
        </div>
    </AppLayout>
</template>
