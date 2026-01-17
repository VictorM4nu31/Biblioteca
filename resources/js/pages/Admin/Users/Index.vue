<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import type { BreadcrumbItem } from '@/types';

interface Props {
    users?: any;
    roles?: any[];
    filters?: any;
}

const props = withDefaults(defineProps<Props>(), {
    users: () => ({ data: [] }),
    roles: () => [],
    filters: () => ({}),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Users'),
        href: '/admin/users',
    },
];
</script>

<template>
    <Head :title="$t('Users')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">{{ $t('Users') }}</h1>

            <div class="grid gap-4">
                <div
                    v-for="user in users.data"
                    :key="user.id"
                    class="rounded-lg border border-sidebar-border/70 bg-card p-4 dark:border-sidebar-border"
                >
                    <h3 class="font-semibold">{{ user.name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ user.email }}</p>
                    <p class="text-sm text-muted-foreground">
                        {{ $t('Items') }}: {{ user.uploaded_items_count }}
                    </p>
                </div>
            </div>

            <div v-if="users.data.length === 0" class="text-center text-muted-foreground">
                {{ $t('No users found') }}
            </div>
        </div>
    </AppLayout>
</template>
