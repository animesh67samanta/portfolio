<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';

interface Props {
    title: string;
    description?: string;
    image?: string;
    noIndex?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    description: '',
    image: '',
    noIndex: false,
});

const page = usePage<PageProps>();
const appName = computed(() => (page.props as any)?.app?.name ?? 'LAVUE');
const fullTitle = computed(() => `${props.title} - ${appName.value}`);
</script>

<template>
    <Head :title="fullTitle">
        <meta v-if="description" name="description" :content="description" />
        <meta property="og:title" :content="fullTitle" />
        <meta v-if="description" property="og:description" :content="description" />
        <meta v-if="image" property="og:image" :content="image" />
        <meta v-if="noIndex" name="robots" content="noindex,nofollow" />
    </Head>
</template>

