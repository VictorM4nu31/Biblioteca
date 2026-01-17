<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    featuredItems?: any[];
    recentItems?: any[];
    popularCategories?: any[];
    publicCollections?: any[];
    stats?: {
        total_items: number;
        total_books: number;
        total_comics: number;
        total_magazines: number;
        total_categories: number;
    };
}

const props = withDefaults(defineProps<Props>(), {
    featuredItems: () => [],
    recentItems: () => [],
    popularCategories: () => [],
    publicCollections: () => [],
    stats: () => ({
        total_items: 0,
        total_books: 0,
        total_comics: 0,
        total_magazines: 0,
        total_categories: 0,
    }),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Home'),
        href: '/',
    },
];
</script>

<template>
    <Head :title="$t('Home')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Estadísticas -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Total Items') }}
                    </div>
                    <div class="text-2xl font-bold">{{ stats.total_items }}</div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Books') }}
                    </div>
                    <div class="text-2xl font-bold">{{ stats.total_books }}</div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Comics') }}
                    </div>
                    <div class="text-2xl font-bold">{{ stats.total_comics }}</div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Magazines') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.total_magazines }}
                    </div>
                </div>
                <div
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <div class="text-sm text-muted-foreground">
                        {{ $t('Categories') }}
                    </div>
                    <div class="text-2xl font-bold">
                        {{ stats.total_categories }}
                    </div>
                </div>
            </div>

            <!-- Items destacados -->
            <div v-if="featuredItems.length > 0">
                <h2 class="mb-4 text-xl font-semibold">
                    {{ $t('Featured Items') }}
                </h2>
                <div
                    class="grid gap-4 md:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="item in featuredItems"
                        :key="item.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-medium">{{ item.title }}</h3>
                        <p v-if="item.ratings_avg_rating" class="text-sm text-muted-foreground">
                            {{ $t('Rating') }}: {{ item.ratings_avg_rating.toFixed(1) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Últimos items -->
            <div v-if="recentItems.length > 0">
                <h2 class="mb-4 text-xl font-semibold">
                    {{ $t('Recent Items') }}
                </h2>
                <div
                    class="grid gap-4 md:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="item in recentItems"
                        :key="item.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-medium">{{ item.title }}</h3>
                        <p class="text-sm text-muted-foreground">
                            {{ item.type }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Categorías populares -->
            <div v-if="popularCategories.length > 0">
                <h2 class="mb-4 text-xl font-semibold">
                    {{ $t('Popular Categories') }}
                </h2>
                <div
                    class="grid gap-4 md:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="category in popularCategories"
                        :key="category.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-medium">{{ category.name }}</h3>
                        <p class="text-sm text-muted-foreground">
                            {{ category.items_count }} {{ $t('items') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Colecciones públicas -->
            <div v-if="publicCollections.length > 0">
                <h2 class="mb-4 text-xl font-semibold">
                    {{ $t('Public Collections') }}
                </h2>
                <div
                    class="grid gap-4 md:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="collection in publicCollections"
                        :key="collection.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-medium">{{ collection.name }}</h3>
                        <p class="text-sm text-muted-foreground">
                            {{ collection.items_count }} {{ $t('items') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
