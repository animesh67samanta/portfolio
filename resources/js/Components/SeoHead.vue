<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { SeoMeta, GlobalSeo } from '@/types/seo';
import type { PageProps } from '@/types';

interface Props {
    meta?: SeoMeta;
}

const props = defineProps<Props>();

const page = usePage<PageProps>();

const global = computed(() => page.props.seo as GlobalSeo | undefined);
const appName = computed(() => page.props.app?.name ?? global.value?.siteName ?? 'HUB Portfolio');
const baseUrl = computed(() => page.props.app?.url ?? global.value?.url ?? (typeof window !== 'undefined' ? window.location.origin : ''));

const imageUrl = (src?: string): string => {
    if (!src) {
        return `${baseUrl.value}/logo.png`;
    }

    if (src.startsWith('http')) {
        return src;
    }

    return `${baseUrl.value}/${src.replace(/^\//, '')}`;
};

const finalMeta = computed<Required<SeoMeta>>(() => {
    const m = props.meta ?? {};
    const g = global.value;

    return {
        title: m.title ?? g?.defaultTitle ?? appName.value,
        description: m.description ?? g?.defaultDescription ?? '',
        keywords: m.keywords ?? g?.defaultKeywords ?? '',
        canonical: m.canonical ?? (typeof window !== 'undefined' ? window.location.href : baseUrl.value),
        image: imageUrl(m.image ?? g?.defaultImage),
        type: m.type ?? 'website',
        publishedAt: m.publishedAt ?? '',
        modifiedAt: m.modifiedAt ?? '',
        author: m.author ?? '',
        twitterCard: m.twitterCard ?? (g?.twitter.card as SeoMeta['twitterCard']) ?? 'summary_large_image',
        robots: m.robots ?? g?.robots ?? 'index, follow',
    };
});
</script>

<template>
    <Head :title="finalMeta.title">
        <!-- Primary Meta -->
        <meta head-key="description" name="description" :content="finalMeta.description" />
        <meta head-key="keywords" name="keywords" :content="finalMeta.keywords" />
        <meta head-key="robots" name="robots" :content="finalMeta.robots" />

        <!-- Canonical -->
        <link head-key="canonical" rel="canonical" :href="finalMeta.canonical" />

        <!-- Open Graph -->
        <meta head-key="og:type" property="og:type" :content="finalMeta.type" />
        <meta head-key="og:site_name" property="og:site_name" :content="appName" />
        <meta head-key="og:locale" property="og:locale" :content="global?.locale ?? 'en_US'" />
        <meta head-key="og:title" property="og:title" :content="finalMeta.title" />
        <meta head-key="og:description" property="og:description" :content="finalMeta.description" />
        <meta head-key="og:url" property="og:url" :content="finalMeta.canonical" />
        <meta head-key="og:image" property="og:image" :content="finalMeta.image" />

        <!-- Article-specific OG -->
        <template v-if="finalMeta.type === 'article'">
            <meta v-if="finalMeta.publishedAt" head-key="article:published_time" property="article:published_time" :content="finalMeta.publishedAt" />
            <meta v-if="finalMeta.modifiedAt" head-key="article:modified_time" property="article:modified_time" :content="finalMeta.modifiedAt" />
            <meta v-if="finalMeta.author" head-key="article:author" property="article:author" :content="finalMeta.author" />
        </template>

        <!-- Twitter / X -->
        <meta head-key="twitter:card" name="twitter:card" :content="finalMeta.twitterCard" />
        <meta v-if="global?.twitter.site" head-key="twitter:site" name="twitter:site" :content="global.twitter.site" />
        <meta v-if="global?.twitter.creator" head-key="twitter:creator" name="twitter:creator" :content="global.twitter.creator" />
        <meta head-key="twitter:title" name="twitter:title" :content="finalMeta.title" />
        <meta head-key="twitter:description" name="twitter:description" :content="finalMeta.description" />
        <meta head-key="twitter:image" name="twitter:image" :content="finalMeta.image" />

        <!-- Facebook App ID (optional) -->
        <meta v-if="global?.facebook.appId" head-key="fb:app_id" property="fb:app_id" :content="global.facebook.appId" />
    </Head>
</template>

