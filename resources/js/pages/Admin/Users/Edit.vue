<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    user?: any;
    roles?: any[];
}

const props = withDefaults(defineProps<Props>(), {
    user: () => ({}),
    roles: () => [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Edit User'),
        href: `/admin/users/${props.user?.id}/edit`,
    },
];
</script>

<template>
    <Head :title="$t('Edit User')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Edit User') }}</h1>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border">
                <p class="text-muted-foreground">{{ $t('Edit form will go here') }}</p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Editing') }}: {{ user.name }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ $t('Roles available') }}: {{ roles.length }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
