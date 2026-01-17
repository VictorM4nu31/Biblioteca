<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    stats?: any;
    itemsByType?: any;
    recentItems?: any[];
    activeUsers?: any[];
    popularCategories?: any[];
    topRatedItems?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    stats: () => ({}),
    itemsByType: () => ({}),
    recentItems: () => [],
    activeUsers: () => [],
    popularCategories: () => [],
    topRatedItems: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Admin Dashboard'),
        href: '/admin/dashboard',
    },
];
</script>

<template>
    <Head :title="$t('Admin Dashboard')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Admin Dashboard') }}</h1>

            <!-- Estadísticas -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Total Users') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.total_users || 0 }}
                    </div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Total Items') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.total_items || 0 }}
                    </div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Total Categories') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.total_categories || 0 }}
                    </div>
                </div>
            </div>

            <!-- Últimos items -->
            <div v-if="recentItems.length > 0">
                <h2 class="mb-4 text-xl font-semibold">
                    {{ $t('Recent Items') }}
                </h2>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="item in recentItems"
                        :key="item.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-semibold">{{ item.title }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
