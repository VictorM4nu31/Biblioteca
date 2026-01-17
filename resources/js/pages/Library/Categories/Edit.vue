<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    category?: any;
}

const props = withDefaults(defineProps<Props>(), {
    category: () => ({}),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Edit Category'),
        href: `/library/categories/${props.category?.id}/edit`,
    },
];
</script>

<template>
    <Head :title="$t('Edit Category')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Edit Category') }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p class="text-muted-foreground">{{ $t('Edit form will go here') }}</p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Editing') }}: {{ category.name }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
