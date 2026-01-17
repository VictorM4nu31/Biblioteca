<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    stats?: any;
    currentlyReading?: any[];
    recentRatings?: any[];
    collections?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    stats: () => ({}),
    currentlyReading: () => [],
    recentRatings: () => [],
    collections: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Dashboard'),
        href: '/user/dashboard',
    },
];
</script>

<template>
    <Head :title="$t('Dashboard')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('User Dashboard') }}</h1>

            <!-- Estadísticas -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Collections') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.total_collections || 0 }}
                    </div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Ratings') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.total_ratings || 0 }}
                    </div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Currently Reading') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.currently_reading || 0 }}
                    </div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Completed') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.completed || 0 }}
                    </div>
                </div>
            </div>

            <!-- Lecturas actuales -->
            <div v-if="currentlyReading.length > 0">
                <h2 class="mb-4 text-xl font-semibold">
                    {{ $t('Currently Reading') }}
                </h2>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="progress in currentlyReading"
                        :key="progress.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-semibold">
                            {{ progress.item?.title }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
