<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    user?: any;
}

const props = withDefaults(defineProps<Props>(), {
    user: () => ({}),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.user?.name || trans('User'),
        href: `/admin/users/${props.user?.id}`,
    },
];
</script>

<template>
    <Head :title="user.name || $t('User')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ user.name }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p class="text-muted-foreground">
                    <strong>{{ $t('Email') }}:</strong> {{ user.email }}
                </p>
                <p v-if="user.uploaded_items" class="text-sm text-muted-foreground">
                    {{ $t('Items uploaded') }}: {{ user.uploaded_items?.length || 0 }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
