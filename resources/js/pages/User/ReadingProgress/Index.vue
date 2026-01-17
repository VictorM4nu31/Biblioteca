<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    reading?: any[];
    completed?: any[];
    wishlist?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    reading: () => [],
    completed: () => [],
    wishlist: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Reading Progress'),
        href: '/user/reading-progress',
    },
];
</script>

<template>
    <Head :title="$t('Reading Progress')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Reading Progress') }}</h1>

            <!-- Leyendo -->
            <div v-if="reading.length > 0">
                <h2 class="mb-4 text-xl font-semibold">{{ $t('Currently Reading') }}</h2>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="progress in reading"
                        :key="progress.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-semibold">{{ progress.item?.title }}</h3>
                        <p class="text-sm text-muted-foreground">
                            {{ progress.current_page }}/{{ progress.total_pages }}
                            {{ $t('pages') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Completados -->
            <div v-if="completed.length > 0">
                <h2 class="mb-4 text-xl font-semibold">{{ $t('Completed') }}</h2>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="progress in completed"
                        :key="progress.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-semibold">{{ progress.item?.title }}</h3>
                    </div>
                </div>
            </div>

            <!-- Lista de deseos -->
            <div v-if="wishlist.length > 0">
                <h2 class="mb-4 text-xl font-semibold">{{ $t('Wishlist') }}</h2>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="progress in wishlist"
                        :key="progress.id"
                        class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                    >
                        <h3 class="font-semibold">{{ progress.item?.title }}</h3>
                    </div>
                </div>
            </div>

            <div
                v-if="reading.length === 0 && completed.length === 0 && wishlist.length === 0"
                class="text-center text-muted-foreground"
            >
                {{ $t('No reading progress found') }}
            </div>
        </div>
    </AppLayout>
</template>
